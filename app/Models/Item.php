<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function description()
    {
        return $this->hasOne(ItemDescription::class, 'id', 'item_description_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')
            ->withCount('ratings')
            ->withCount('positive_ratings')
            ->withCount('negative_ratings');
    }

    public function section()
    {
        return $this->hasOne(ItemSection::class, 'id', 'item_section_id');
    }

    public function category()
    {
        return $this->hasOne(ItemCategory::class, 'id', 'item_category_id');
    }

    public function condition()
    {
        return $this->hasOne(ItemCondition::class, 'id', 'item_condition_id');
    }

    public function warranty()
    {
        return $this->hasOne(ItemWarranty::class, 'id', 'item_warranty_id');
    }

    public function views()
    {
        return $this->hasMany(ItemView::class, 'item_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(ItemLike::class, 'item_id', 'id');
    }

    public function bookmarks()
    {
        return $this->hasMany(ItemBookmark::class, 'item_id', 'id');
    }
}
