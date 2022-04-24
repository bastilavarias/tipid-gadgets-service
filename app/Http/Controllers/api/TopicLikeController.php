<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TopicLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicLikeController extends Controller
{
    public function store(Request $request)
    {
        $foundTopicLike = TopicLike::where([
            'topic_id' => $request->input('topic_id'),
            'user_id' => Auth::id(),
        ])
            ->get()
            ->first();
        if (!empty($foundTopicLike)) {
            $foundTopicLike->delete();
            return customResponse()
                ->data(null)
                ->message('You have removed your like in this topic.')
                ->success()
                ->generate();
        }
        TopicLike::create([
            'topic_id' => $request->input('topic_id'),
            'user_id' => Auth::id(),
        ]);
        return customResponse()
            ->data(null)
            ->message('You liked this topic.')
            ->success()
            ->generate();
    }

    public function check($topicID)
    {
        $foundTopicLike = TopicLike::where([
            'topic_id' => $topicID,
            'user_id' => Auth::id(),
        ])
            ->get()
            ->first();
        $isLiked = !empty($foundTopicLike);
        return customResponse()
            ->data($isLiked)
            ->message('You have successfully check if the user liked this topic post.')
            ->success()
            ->generate();
    }

    public function count($topicID)
    {
        $count = TopicLike::where([
            'topic_id' => $topicID,
        ])->count();
        return customResponse()
            ->data($count)
            ->message('You have successfully topic likes count.')
            ->success()
            ->generate();
    }
}
