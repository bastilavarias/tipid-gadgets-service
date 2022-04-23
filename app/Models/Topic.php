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
}
