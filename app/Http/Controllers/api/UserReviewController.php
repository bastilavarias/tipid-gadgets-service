<?php

namespace App\Http\Controllers\api;

use App\Events\user\ReviewEvent;
use App\Http\Controllers\Controller;
use App\Models\UserReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReviewController extends Controller
{
    public function store(Request $request)
    {
        $reviewerID = Auth::id();
        $review = UserReview::where([
            'reviewer_id' => $reviewerID,
            'reviewee_id' => $request->input('user_id'),
            'transaction_id' => $request->input('transaction_id'),
        ])
            ->get()
            ->first();
        if (!empty($review)) {
            return customResponse()
                ->data(null)
                ->message('You already created a review.')
                ->failed()
                ->generate();
        }
        $review = UserReview::create([
            'reviewer_id' => $reviewerID,
            'reviewee_id' => $request->input('user_id'),
            'transaction_id' => $request->input('transaction_id'),
            'content' => $request->input('content'),
            'rating' => $request->input('rating'),
        ]);
        $review = UserReview::with(['transaction'])->find($review->id);
        event(new ReviewEvent($review->transaction->message_room_id, $review));
        return customResponse()
            ->data($review)
            ->message('You successfully created a review.')
            ->success()
            ->generate();
    }
}
