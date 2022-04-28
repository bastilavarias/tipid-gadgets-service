<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\TopicComment;
use App\Models\TopicCommentReply;
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
        if (!empty($request->comment_id)) {
            TopicCommentReply::create([
                'comment_id' => $request->comment_id,
                'reply_id' => $comment->id,
            ]);
        }
        $comment = TopicComment::with(['topic', 'replyTo'])->find($comment->id);
        return customResponse()
            ->data($comment)
            ->message('You have successfully posted a comment.')
            ->success()
            ->generate();
    }

    public function index(Request $request, $topicID)
    {
        $sortBy = $request->sort_by ? $request->sort_by : 'created_at';
        $orderBy = $request->order_by ? $request->order_by : 'desc';
        $page = $request->page ? intval($request->page) : 1;
        $perPage = $request->per_page ? intval($request->per_page) : 10;
        $comments = TopicComment::with(['topic', 'replyTo'])
            ->where('topic_id', '=', $topicID)
            ->orderBy($sortBy, $orderBy)
            ->paginate($perPage, ['*'], 'page', $page);
        return customResponse()
            ->data($comments)
            ->message('You have successfully get topic comments.')
            ->success()
            ->generate();
    }
    public function count($topicID)
    {
        $count = TopicComment::where([
            'topic_id' => $topicID,
        ])->count();
        return customResponse()
            ->data($count)
            ->message('You have successfully topic comments count.')
            ->success()
            ->generate();
    }
}
