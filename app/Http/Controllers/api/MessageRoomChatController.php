<?php

namespace App\Http\Controllers\api;

use App\Events\message\ChatEvent;
use App\Events\message\RoomEvent;
use App\Http\Controllers\Controller;
use App\Models\MessageRoom;
use App\Models\MessageRoomChat;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageRoomChatController extends Controller
{
    public function index(Request $request, $roomID)
    {
        $page = $request->page ? intval($request->page) : 1;
        $perPage = $request->per_page ? intval($request->per_page) : 10;
        $chats = MessageRoomChat::with(['user'])
            ->where('message_room_id', $roomID)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        $chats = $chats->reverse()->values();
        return customResponse()
            ->data($chats)
            ->message('You successfully got room chats.')
            ->success()
            ->generate();
    }

    public function store(Request $request)
    {
        $chat = MessageRoomChat::create([
            'message_room_id' => $request->input('room_id'),
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
        ]);
        $chat = MessageRoomChat::with(['user'])->find($chat->id);
        $room = MessageRoom::with(['host', 'customer', 'item', 'recent_chat'])
            ->where('id', $chat->message_room_id)
            ->addSelect([
                'recent_chat_id' => MessageRoomChat::whereColumn(
                    'message_rooms.id',
                    'message_room_id'
                )
                    ->latest()
                    ->select('id')
                    ->limit(1),
            ])
            ->latest()
            ->get()
            ->first();
        $room->touch();
        event(new RoomEvent($room->host_user_id, $room));
        event(new RoomEvent($room->customer_user_id, $room));
        event(new ChatEvent($room->id, $chat));
        return customResponse()
            ->data($room)
            ->message('You successfully sent an chat.')
            ->success()
            ->generate();
    }
}
