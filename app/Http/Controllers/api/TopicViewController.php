<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TopicView;
use Illuminate\Http\Request;

class TopicViewController extends Controller
{
    public function store(Request $request)
    {
        if (!empty($request->input('user_id'))) {
            $foundTopicView = TopicView::where([
                'topic_id' => $request->input('topic_id'),
                'user_id' => $request->input('user_id'),
            ])
                ->get()
                ->first();
            if (!empty($foundTopicView)) {
                return customResponse()
                    ->data(null)
                    ->message('You have successfully viewed an topic.')
                    ->success()
                    ->generate();
            }
        }
        TopicView::create([
            'topic_id' => $request->input('topic_id'),
            'user_id' => $request->input('user_id'),
        ]);
        return customResponse()
            ->data(null)
            ->message('You have successfully viewed an topic.')
            ->success()
            ->generate();
    }

    public function count($topicID)
    {
        $count = TopicView::where([
            'topic_id' => $topicID,
        ])->count();
        return customResponse()
            ->data($count)
            ->message('You have successfully topic views count.')
            ->success()
            ->generate();
    }
}
