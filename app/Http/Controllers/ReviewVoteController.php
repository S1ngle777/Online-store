<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewVoteController extends Controller
{
    public function vote(Request $request, Review $review)
    {
        $validated = $request->validate([
            'vote_type' => 'required|in:like,dislike'
        ]);

        $existingVote = $review->votes()
            ->where('user_id', auth()->id())
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === $validated['vote_type']) {
                $existingVote->delete();
                $message = __('reviews.vote_cancelled');
            } else {
                $existingVote->update(['vote_type' => $validated['vote_type']]);
                $message = __('reviews.vote_changed'); 
            }
        } else {
            $review->votes()->create([
                'user_id' => auth()->id(),
                'vote_type' => $validated['vote_type']
            ]);
            $message = __('reviews.vote_recorded');
        }

        // Обновляем счетчики
        $review->update([
            'likes' => $review->votes()->where('vote_type', 'like')->count(),
            'dislikes' => $review->votes()->where('vote_type', 'dislike')->count()
        ]);

        return back()->with('success', $message);
    }
}
