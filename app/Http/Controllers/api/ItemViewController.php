<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ItemView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemViewController extends Controller
{
    public function store(Request $request)
    {
        if (!empty($request->input('user_id'))) {
            $foundItemView = ItemView::where([
                'item_id' => $request->input('item_id'),
                'user_id' => $request->input('user_id'),
            ])
                ->get()
                ->first();
            if (!empty($foundItemView)) {
                return customResponse()
                    ->data(null)
                    ->message('You have successfully viewed an item.')
                    ->success()
                    ->generate();
            }
        }
        ItemView::create([
            'item_id' => $request->input('item_id'),
            'user_id' => $request->input('user_id'),
        ]);
        return customResponse()
            ->data(null)
            ->message('You have successfully viewed an item.')
            ->success()
            ->generate();
    }

    public function count($itemID)
    {
        $count = ItemView::where([
            'item_id' => $itemID,
        ])->count();
        return customResponse()
            ->data($count)
            ->message('You have successfully item views count.')
            ->success()
            ->generate();
    }
}
