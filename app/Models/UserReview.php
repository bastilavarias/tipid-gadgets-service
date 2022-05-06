<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transaction()
    {
        return $this->hasOne(ItemTransaction::class, 'id', 'transaction_id');
    }

    public function reviewer()
    {
        return $this->hasOne(User::class, 'id', 'reviewer_id');
    }
}
