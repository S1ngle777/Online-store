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
            return back()->with('error', __('reviews.purchase_required'));
        }

        // Проверяем, не оставлял ли пользователь уже отзыв
        if ($product->reviews()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', __('reviews.already_reviewed'));
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

        return back()->with('success', __('reviews.created_successfully'));
    }

    public function destroy(Review $review)
    {
        if (auth()->id() === $review->user_id || auth()->user()->isAdmin()) {
            $review->delete();
            return back()->with('success', __('reviews.deleted_successfully'));
        }
        return back()->with('error', __('reviews.delete_unauthorized'));
    }

    public function replyAsAdmin(Request $request, Review $review)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', __('reviews.admin_only'));
        }

        $validated = $request->validate([
            'admin_reply' => 'required|string|max:1000'
        ]);

        $review->update([
            'admin_reply' => $validated['admin_reply'],
            'admin_reply_at' => now()
        ]);

        return back()->with('success', __('reviews.reply_added'));
    }

}
