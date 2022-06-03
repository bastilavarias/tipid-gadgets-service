<?php

namespace App\Http\Controllers\api;

use App\Events\user\NotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Topic;
use App\Models\TopicComment;
use App\Models\TopicCommentReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicCommentController extends Controller
{
    public function store(Request $request)
    {
        $topicID = $request->input('topic_id');
        $commenterID = Auth::id();
        $comment = TopicComment::create([
            'content' => $request->input('content'),
            'topic_id' => $topicID,
            'user_id' => $commenterID,
        ]);
        if (!empty($request->comment_id)) {
            TopicCommentReply::create([
                'comment_id' => $request->comment_id,
                'reply_id' => $comment->id,
            ]);
        }
        $topic = Topic::find($topicID);
        $topic->touch();
        $comment = $topic
            ->comments()
            ->with(['topic', 'replyTo'])
            ->find($comment->id);
        if ($topic->user_id !== $commenterID) {
            $notification = Notification::create([
                'type' => 'comment',
                'action' => 'comment',
                'comment_id' => $comment->id,
            ]);
            $notification = Notification::with(['comment'])->find($notification->id);
            event(new NotificationEvent($topic->user_id, $notification));
        }
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
