<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicComment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['user'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function topic()
    {
        return $this->hasOne(Topic::class, 'id', 'topic_id');
    }

    public function replyTo()
    {
        return $this->belongsTo(TopicCommentReply::class, 'id', 'reply_id');
    }
}
