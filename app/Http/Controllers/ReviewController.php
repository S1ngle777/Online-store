<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        // Проверяем, купил ли пользователь товар
        if (!auth()->user()->hasPurchased($product)) {
            return back()->with('error', 'Вы можете оставить отзыв только после покупки товара');
        }

        // Проверяем, не оставлял ли пользователь уже отзыв
        if ($product->reviews()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'Вы уже оставляли отзыв на этот товар');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $product->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);

        return back()->with('success', 'Отзыв успешно добавлен');
    }

    public function destroy(Review $review)
    {
        if (auth()->id() === $review->user_id || auth()->user()->isAdmin()) {
            $review->delete();
            return back()->with('success', 'Отзыв удален');
        }
        return back()->with('error', 'У вас нет прав для удаления этого отзыва');
    }

    public function replyAsAdmin(Request $request, Review $review)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Только администратор может отвечать на отзывы');
        }

        $validated = $request->validate([
            'admin_reply' => 'required|string|max:1000'
        ]);

        $review->update([
            'admin_reply' => $validated['admin_reply'],
            'admin_reply_at' => now()
        ]);

        return back()->with('success', 'Ответ добавлен');
    }

}
