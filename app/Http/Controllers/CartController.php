<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $totalPrice = 0;
        $totalOriginalPrice = 0;
        
        foreach ($cartItems as $id => &$item) {
            // Parse product ID from cart key (handles both normal products and products with sizes)
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
        session()->put('cart', $cartItems);

        return view('cart.index', compact('cartItems', 'totalPrice', 'totalSaving', 'totalOriginalPrice'));
    }

    public function add(Request $request, Product $product)
    {
        $quantity = (int)$request->input('quantity', 1);
        $sizeId = $request->input('size');

        if ($product->has_sizes && !$sizeId) {
            return redirect()->back()->with('error', 'Пожалуйста, выберите размер');
        }

        $cart = session()->get('cart', []);
        $cartKey = $product->has_sizes ? $product->id . '-' . $sizeId : $product->id;

        // Check size stock
        if ($product->has_sizes) {
            $size = Size::find($sizeId);
            if (!$size) {
                return redirect()->back()->with('error', 'Выбранный размер недоступен');
            }

            $sizeStock = $product->getSizeStock($sizeId);
            $currentCartQuantity = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
            
            if ($currentCartQuantity + $quantity > $sizeStock) {
                return redirect()->back()
                    ->with('error', "Недостаточно товара выбранного размера. Доступно: {$sizeStock} шт.");
            }
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'name' => $product->name,
                'price' => $product->hasActiveDiscount() ? $product->discounted_price : $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
                'size_id' => $sizeId,
                'size_name' => $size ? $size->name : null
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Продукт добавлен в корзину!');
    }

    public function remove(Request $request, $cartKey)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Товар удален из корзины!');
        }

        return redirect()->back()->with('error', 'Товар не найден в корзине');
    }
}
