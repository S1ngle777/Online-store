<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\DeliveryMethod;
use App\Notifications\OrderCompleted;
use Illuminate\Http\Request;
use App\Notifications\OrderCreated;

class OrderController extends Controller
{
    public function checkout()
    {
        $cartItems = session()->get('cart', []);
        $totalPrice = 0;
        $totalOriginalPrice = 0;
        $deliveryMethods = DeliveryMethod::where('is_active', true)->get();

        // Update prices for all items in cart
        foreach ($cartItems as $id => &$item) {
            // Parse product ID from cart key (for products with sizes, the key is in format "productId-sizeId")
            $productId = explode('-', $id)[0];
            $product = Product::find($productId);

            if ($product) {
                $item['original_price'] = $product->price;
                $item['price'] = $product->hasActiveDiscount() ? $product->discounted_price : $product->price;
                $totalPrice += $item['price'] * $item['quantity'];
                $totalOriginalPrice += $item['original_price'] * $item['quantity'];
            }
        }

        $totalSaving = $totalOriginalPrice - $totalPrice;

        // Сохраняем обновленную корзину с актуальными ценами
        session()->put('cart', $cartItems);

        $defaultAddress = auth()->user()->default_address ?? '';

        return view('orders.checkout', compact(
            'cartItems',
            'totalPrice',
            'totalOriginalPrice',
            'totalSaving',
            'deliveryMethods',
            'defaultAddress'
        ));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => [
                    'required',
                    'regex:/^[0-9]+$/',
                    'min:8',
                    'max:15'
                ],
                'address' => 'required|string',
                'notes' => 'nullable|string',
                'delivery_method_id' => 'required|exists:delivery_methods,id',
                'payment_method' => 'required|in:cash,card',
                'save_address' => 'nullable|boolean'
            ]);

            $cartItems = session()->get('cart', []);
            if (empty($cartItems)) {
                throw new \Exception('Корзина пуста');
            }

            // Calculate subtotal before creating order
            $subtotal = 0;
            foreach ($cartItems as $cartKey => $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            // Get delivery method and add its cost to total
            $deliveryMethod = DeliveryMethod::findOrFail($validated['delivery_method_id']);
            $totalAmount = $subtotal + $deliveryMethod->price;

            // Create order with total amount
            $order = Order::create([
                'user_id' => auth()->id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'notes' => $validated['notes'],
                'status' => 'pending',
                'delivery_method_id' => $deliveryMethod->id,
                'payment_method' => $validated['payment_method'],
                'total_amount' => $totalAmount // Add this line
            ]);

            // Process cart items...
            foreach ($cartItems as $cartKey => $item) {
                $parts = explode('-', $cartKey);
                $productId = $parts[0];
                $sizeId = isset($parts[1]) ? $parts[1] : null;

                $product = Product::findOrFail($productId);

                // Check stock...
                if ($product->has_sizes) {
                    $sizeStock = $product->getSizeStock($sizeId);
                    if ($sizeStock < $item['quantity']) {
                        throw new \Exception("Недостаточно товара {$item['name']} выбранного размера на складе");
                    }
                } else {
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Недостаточно товара {$item['name']} на складе");
                    }
                }

                // Create order item with size
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'size_id' => $sizeId, // Add size_id
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // Update stock handling section
                if ($product->has_sizes) {
                    // Update size-specific stock
                    $product->sizes()->updateExistingPivot($sizeId, [
                        'stock' => \DB::raw('stock - ' . $item['quantity'])
                    ]);
                    // Update total product stock
                    $product->decrement('stock', $item['quantity']);
                } else {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            // Save address if requested
            if ($request->boolean('save_address')) {
                auth()->user()->update([
                    'default_address' => $validated['address']
                ]);
            }

            // Send notification
            $order->notify(new OrderCreated($order));

            \DB::commit();
            session()->forget('cart');

            return redirect()->route('orders.success', $order)
                ->with('success', 'Заказ успешно оформлен! Проверьте вашу почту.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        return view('orders.success', compact('order'));
    }

    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if (auth()->user()->isAdmin() || auth()->id() === $order->user_id) {
            return view('orders.show', compact('order'));
        }
        return abort(403);
    }

    public function updateStatus(Request $request, Order $order)
    {
        try {
            \DB::beginTransaction();

            $validated = $request->validate([
                'status' => 'required|in:pending,processing,completed,cancelled'
            ]);

            $oldStatus = $order->status;
            $newStatus = $validated['status'];

            // Если заказ отменяется, возвращаем товары на склад
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        if ($product->has_sizes && $item->size_id) {
                            $product->sizes()->updateExistingPivot($item->size_id, [
                                'stock' => \DB::raw('stock + ' . $item->quantity)
                            ]);
                            $product->increment('stock', $item->quantity);
                        } else {
                            $product->increment('stock', $item->quantity);
                        }
                    }
                }
            }
            // Если отмененный заказ восстанавливается
            elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        if ($product->has_sizes && $item->size_id) {
                            $product->sizes()->updateExistingPivot($item->size_id, [
                                'stock' => \DB::raw('stock - ' . $item->quantity)
                            ]);
                            $product->decrement('stock', $item->quantity);
                        } else {
                            $product->decrement('stock', $item->quantity);
                        }
                    }
                }
            }

            $order->update(['status' => $newStatus]);

            // Отправляем уведомление при завершении заказа
            if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                $order->notify(new OrderCompleted($order));
            }

            \DB::commit();

            return redirect()->back()->with('success', 'Статус заказа обновлен');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Ошибка при обновлении статуса: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        try {
            \DB::beginTransaction();

            // If order is not cancelled, restore stock before deletion
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        if ($product->has_sizes && $item->size_id) {
                            // Restore size-specific stock
                            $product->sizes()->updateExistingPivot($item->size_id, [
                                'stock' => \DB::raw('stock + ' . $item->quantity)
                            ]);
                            // Restore total product stock
                            $product->increment('stock', $item->quantity);
                        } else {
                            // Restore general product stock
                            $product->increment('stock', $item->quantity);
                        }
                    }
                }
            }

            $order->delete();
            \DB::commit();

            return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно удален');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Ошибка при удалении заказа: ' . $e->getMessage());
        }
    }

    public function adminIndex()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }
}