<?php

namespace App\Http\Controllers\api;

use App\Events\user\ReviewEvent;
use App\Http\Controllers\Controller;
use App\Models\Item;
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
        $review = UserReview::with(['transaction', 'reviewer'])->find($review->id);
        event(new ReviewEvent($review->transaction->message_room_id, $review));
        return customResponse()
            ->data($review)
            ->message('You successfully created a review.')
            ->success()
            ->generate();
    }

    public function check($userID, $transactionID)
    {
        $review = UserReview::where([
            'transaction_id' => $transactionID,
            'reviewer_id' => Auth::id(),
            'reviewee_id' => $userID,
        ])
            ->get()
            ->first();
        if (empty($review)) {
            return customResponse()
                ->data(true)
                ->message('You valid to review.')
                ->success()
                ->generate();
        }
        return customResponse()
            ->data(false)
            ->message('You not valid to review.')
            ->success()
            ->generate();
    }

    public function index(Request $request)
    {
        $page = $request->page ? intval($request->page) : 1;
        $perPage = $request->per_page ? intval($request->per_page) : 10;
        $userID = $request->user_id ? $request->user_id : null;
        $query = UserReview::query();
        if (!empty($userID)) {
            $query = $query->where('reviewee_id', '=', $userID);
        }
        $query
            ->with(['reviewer', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        $items = $query->get();
        return customResponse()
            ->data($items)
            ->message('You have successfully get user reviews.')
            ->success()
            ->generate();
    }
}
