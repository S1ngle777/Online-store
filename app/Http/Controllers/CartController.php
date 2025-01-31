<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $totalPrice = 0;
        $totalOriginalPrice = 0;
        
        // Обновляем цены для всех товаров в корзине
        foreach ($cartItems as $id => &$item) {
            $product = Product::find($id);
            if ($product) {
                $item['original_price'] = $product->price; // Сохраняем оригинальную цену
                $item['price'] = $product->hasActiveDiscount() ? $product->discounted_price : $product->price;
                $totalPrice += $item['price'] * $item['quantity'];
                $totalOriginalPrice += $item['original_price'] * $item['quantity'];
            }
        }
        
        $totalSaving = $totalOriginalPrice - $totalPrice;
        
        // Сохраняем обновленную корзину
        session()->put('cart', $cartItems);

        return view('cart.index', compact('cartItems', 'totalPrice', 'totalSaving', 'totalOriginalPrice'));
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        $cart = session()->get('cart', []);

        // Проверяем доступное количество
        $currentCartQuantity = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;
        $newTotalQuantity = $currentCartQuantity + $quantity;

        if ($newTotalQuantity > $product->stock) {
            return redirect()->back()
                ->with('error', "Недостаточно товара на складе. Доступно: {$product->stock} шт.");
        }

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
            // Обновляем цену при каждом добавлении
            $cart[$product->id]['price'] = $product->hasActiveDiscount() ? $product->discounted_price : $product->price;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->hasActiveDiscount() ? $product->discounted_price : $product->price,
                'quantity' => $quantity,
                'image' => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Продукт добавлен в корзину!');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Продукт удален из корзины!');
    }
}
