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
}
