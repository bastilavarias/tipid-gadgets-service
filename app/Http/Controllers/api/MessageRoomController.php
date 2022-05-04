<?php

namespace App\Http\Controllers\api;

use App\Events\MessageRoomEvent;
use App\Http\Controllers\Controller;
use App\Models\MessageRoom;
use App\Models\MessageRoomChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageRoomController extends Controller
{
    public function store(Request $request)
    {
        $hostUserID = $request->input('user_id');
        $customerUserID = Auth::id();
        $room = MessageRoom::where('item_id', $request->input('item_id'))
            ->where('host_user_id', $hostUserID)
            ->where('customer_user_id', $customerUserID)
            ->get()
            ->first();
        if (!empty($room)) {
            return customResponse()
                ->data($room)
                ->message('You successfully created message room.')
                ->success()
                ->generate();
        }
        $room = MessageRoom::create([
            'item_id' => $request->input('item_id'),
            'host_user_id' => $hostUserID,
            'customer_user_id' => $customerUserID,
        ]);
        MessageRoomChat::create([
            'content' => 'Hi, is this available?',
            'user_id' => $customerUserID,
            'message_room_id' => $room->id,
        ]);
        return customResponse()
            ->data($room)
            ->message('You successfully created a message room.')
            ->success()
            ->generate();
    }

    public function getUserRooms(Request $request)
    {
        $page = $request->page ? intval($request->page) : 1;
        $perPage = $request->per_page ? intval($request->per_page) : 10;
        $userID = Auth::id();
        $rooms = MessageRoom::with(['host', 'customer', 'item', 'recent_chat'])
            ->addSelect([
                'recent_chat_id' => MessageRoomChat::whereColumn(
                    'message_rooms.id',
                    'message_room_id'
                )
                    ->latest()
                    ->select('id')
                    ->limit(1),
            ])
            ->where('host_user_id', $userID)
            ->orWhere('customer_user_id', $userID)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        return customResponse()
            ->data($rooms)
            ->message('You successfully got user rooms.')
            ->success()
            ->generate();
    }

    public function show($roomID)
    {
        $room = MessageRoom::with(['host', 'customer', 'item', 'recent_chat'])
            ->addSelect([
                'recent_chat_id' => MessageRoomChat::whereColumn(
                    'message_rooms.id',
                    'message_room_id'
                )
                    ->latest()
                    ->select('id')
                    ->limit(1),
            ])
            ->find($roomID);
        if (empty($room)) {
            return customResponse()
                ->data(null)
                ->message('Room not found.')
                ->notFound()
                ->generate();
        }
        return customResponse()
            ->data($room)
            ->message('You successfully got room.')
            ->success()
            ->generate();
    }
}
