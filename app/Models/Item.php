<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function description()
    {
        return $this->hasOne(ItemDescription::class, 'id', 'item_description_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function itemSection()
    {
        return $this->hasOne(ItemSection::class, 'id', 'item_section_id');
    }

    public function itemCategory()
    {
        return $this->hasOne(ItemCategory::class, 'id', 'item_category_id');
    }

    public function itemCondition()
    {
        return $this->hasOne(ItemCondition::class, 'id', 'item_condition_id');
    }

    public function itemWarranty()
    {
        return $this->hasOne(ItemWarranty::class, 'id', 'item_warranty_id');
    }
}
