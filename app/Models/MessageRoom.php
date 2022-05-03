<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageRoom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function host()
    {
        return $this->hasOne(User::class, 'id', 'host_user_id');
    }

    public function customer()
    {
        return $this->hasOne(User::class, 'id', 'customer_user_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function chats()
    {
        return $this->hasMany(MessageRoomChat::class, 'message_room_id', 'id')->latest();
    }
}
