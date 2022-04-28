<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TopicComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicCommentController extends Controller
{
    public function store(Request $request)
    {
        $comment = TopicComment::create([
            'content' => $request->input('content'),
            'topic_id' => $request->input('topic_id'),
            'user_id' => Auth::id(),
        ]);
        return customResponse()
            ->data($comment)
            ->message('You have successfully posted a comment.')
            ->success()
            ->generate();
    }
}
