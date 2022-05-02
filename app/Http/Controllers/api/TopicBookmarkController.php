<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TopicBookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicBookmarkController extends Controller
{
    public function store(Request $request)
    {
        $foundTopicBookmark = TopicBookmark::where([
            'topic_id' => $request->input('topic_id'),
            'user_id' => Auth::id(),
        ])
            ->get()
            ->first();
        if (!empty($foundTopicBookmark)) {
            $foundTopicBookmark->delete();
            return customResponse()
                ->data(null)
                ->message('You have removed your bookmark in this topic.')
                ->success()
                ->generate();
        }
        TopicBookmark::create([
            'topic_id' => $request->input('topic_id'),
            'user_id' => Auth::id(),
        ]);
        return customResponse()
            ->data(null)
            ->message('You have bookmarked this topic.')
            ->success()
            ->generate();
    }

    public function check($topicID)
    {
        $foundTopicBookmark = TopicBookmark::where([
            'topic_id' => $topicID,
            'user_id' => Auth::id(),
        ])
            ->get()
            ->first();
        $isBookmarked = !empty($foundTopicBookmark);
        return customResponse()
            ->data($isBookmarked)
            ->message(
                'You have successfully check if the user bookmarked this topic post.'
            )
            ->success()
            ->generate();
    }

    public function index(Request $request)
    {
        $sortBy = $request->sort_by ? $request->sort_by : 'created_at';
        $orderBy = $request->order_by ? $request->order_by : 'desc';
        $page = $request->page ? intval($request->page) : 1;
        $perPage = $request->per_page ? intval($request->per_page) : 10;
        $userID = $request->user_id ? $request->user_id : null;
        $query = TopicBookmark::query();
        if (!empty($userID)) {
            $query = $query->where('user_id', '=', $userID);
        }
        $query
            ->with(['topic'])
            ->orderBy($sortBy, $orderBy)
            ->paginate($perPage, ['*'], 'page', $page);
        $bookmarks = $query->get();
        return customResponse()
            ->data($bookmarks)
            ->message('You have successfully get user topic bookmarks.')
            ->success()
            ->generate();
    }
}
