<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicCommentReply extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['comment'];

    public function comment()
    {
        return $this->hasOne(TopicComment::class, 'id', 'comment_id');
    }
}
