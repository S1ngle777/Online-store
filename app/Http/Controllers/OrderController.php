<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\DeliveryMethod;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout()
    {
        $cartItems = session()->get('cart', []);
        $totalPrice = 0;
        $totalOriginalPrice = 0;
        $deliveryMethods = DeliveryMethod::where('is_active', true)->get();

        // Обновляем цены для всех товаров в корзине перед оформлением заказа
        foreach ($cartItems as $id => &$item) {
            $product = Product::find($id);
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

        try {
            \DB::beginTransaction();

            $cartItems = session()->get('cart', []);
            if (empty($cartItems)) {
                throw new \Exception('Корзина пуста');
            }

            // Получить метод доставки и рассчитать итоговые суммы
            $deliveryMethod = DeliveryMethod::findOrFail($validated['delivery_method_id']);
            $subtotal = 0;

            foreach ($cartItems as $id => $item) {
                $product = Product::find($id);
                if (!$product) {
                    throw new \Exception("Товар {$item['name']} больше не доступен");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Недостаточно товара {$item['name']} на складе. Доступно: {$product->stock} шт.");
                }
                
                $subtotal += $item['price'] * $item['quantity'];
            }

            // Рассчитать итоговую сумму с доставкой
            $totalAmount = $subtotal + $deliveryMethod->price;

            // Создать заказ
            $order = Order::create([
                'user_id' => auth()->id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'notes' => $validated['notes'],
                'total_amount' => $totalAmount, // Now includes delivery cost
                'status' => 'pending',
                'delivery_method_id' => $deliveryMethod->id,
                'payment_method' => $validated['payment_method']
            ]);

            // Создать товары заказа и уменьшить запас
            foreach ($cartItems as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                $product = Product::find($id);
                $product->decrement('stock', $item['quantity']);
            }

            // Сохранить адрес, если запрошено
            if ($request->has('save_address') && $request->boolean('save_address') && auth()->check()) {
                auth()->user()->update([
                    'default_address' => $validated['address']
                ]);
            }

            \DB::commit();
            session()->forget('cart');

            return redirect()->route('orders.success', $order)
                ->with('success', 'Заказ успешно оформлен!');

        } catch (\Exception $e) {
            \DB::rollBack();
            
            // Сохранить данные формы в сессии
            session()->flash('delivery_method_id', $request->delivery_method_id);
            session()->flash('payment_method', $request->payment_method);
            
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
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
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

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
                    $product->increment('stock', $item->quantity);
                }
            }
        }
        // Если отмененный заказ возвращается в обработку, снова уменьшаем количество товаров
        elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }
        }

        $order->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Статус заказа обновлен');
    }

    // Добавим метод для удаления заказа
    public function destroy(Order $order)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        // Если заказ не отменен, возвращаем товары на склад перед удалением
        if ($order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Заказ успешно удален');
    }

    public function adminIndex()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }
}