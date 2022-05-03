<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\MessageRoomChat;
use Illuminate\Http\Request;

class MessageRoomChatController extends Controller
{
    public function index(Request $request, $roomID)
    {
        $page = $request->page ? intval($request->page) : 1;
        $perPage = $request->per_page ? intval($request->per_page) : 10;
        $chats = MessageRoomChat::where('message_room_id', $roomID)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        $chats = $chats->reverse()->values();
        return customResponse()
            ->data($chats)
            ->message('You successfully got room chats.')
            ->success()
            ->generate();
    }
}
