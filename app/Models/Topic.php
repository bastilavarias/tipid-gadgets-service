<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function description()
    {
        return $this->hasOne(TopicDescription::class, 'id', 'topic_description_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function section()
    {
        return $this->hasOne(TopicSection::class, 'id', 'topic_section_id');
    }

    public function views()
    {
        return $this->hasMany(TopicView::class, 'topic_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(TopicLike::class, 'topic_id', 'id');
    }

    public function bookmarks()
    {
        return $this->hasMany(TopicBookmark::class, 'topic_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(TopicComment::class, 'topic_id', 'id');
    }
}
