<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function topic()
    {
        return $this->hasOne(Topic::class, 'id', 'topic_id');
    }

    public function comment()
    {
        return $this->hasOne(TopicComment::class, 'id', 'comment_id')->with(['topic']);
    }
}
