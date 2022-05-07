<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
