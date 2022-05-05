<?php

namespace App\Http\Controllers\api;

use App\Events\item\TransactionEvent;
use App\Http\Controllers\Controller;
use App\Models\ItemTransaction;
use Illuminate\Http\Request;

class ItemTransactionController extends Controller
{
    public function receive(Request $request)
    {
        $roomID = $request->input('room_id');
        $transaction = ItemTransaction::where([
            'item_id' => $request->input('item_id'),
            'message_room_id' => $roomID,
        ])
            ->get()
            ->first();
        if (empty($transaction)) {
            return customResponse()
                ->data(null)
                ->message('Transaction not found.')
                ->notFound()
                ->generate();
        }
        $transaction = tap($transaction)->update([
            'status' => 'received',
        ]);
        event(new TransactionEvent($roomID, $transaction));
        return customResponse()
            ->data($transaction)
            ->message('You marked this transaction received.')
            ->success()
            ->generate();
    }
}
