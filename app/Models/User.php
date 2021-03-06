<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'user_id', 'id');
    }

    public function ratings()
    {
        return $this->hasMany(UserReview::class, 'reviewee_id', 'id');
    }

    public function positive_ratings()
    {
        return $this->hasMany(UserReview::class, 'reviewee_id', 'id')->where(
            'rating',
            'positive'
        );
    }

    public function negative_ratings()
    {
        return $this->hasMany(UserReview::class, 'reviewee_id', 'id')->where(
            'rating',
            'negative'
        );
    }
}
