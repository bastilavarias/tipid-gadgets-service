<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ItemLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemLikeController extends Controller
{
    public function store(Request $request)
    {
        $foundItemLike = ItemLike::where([
            'item_id' => $request->input('item_id'),
            'user_id' => Auth::id(),
        ])
            ->get()
            ->first();
        if (!empty($foundItemLike)) {
            $foundItemLike->delete();
            return customResponse()
                ->data(null)
                ->message('You have removed your like in this item.')
                ->success()
                ->generate();
        }
        ItemLike::create([
            'item_id' => $request->input('item_id'),
            'user_id' => Auth::id(),
        ]);
        return customResponse()
            ->data(null)
            ->message('You liked this item.')
            ->success()
            ->generate();
    }

    public function check($itemID)
    {
        $foundItemLike = ItemLike::where([
            'item_id' => $itemID,
            'user_id' => Auth::id(),
        ])
            ->get()
            ->first();
        $isLiked = !empty($foundItemLike);
        return customResponse()
            ->data($isLiked)
            ->message('You have successfully check if the user liked this item post.')
            ->success()
            ->generate();
    }

    public function count($itemID)
    {
        $count = ItemLike::where([
            'item_id' => $itemID,
        ])->count();
        return customResponse()
            ->data($count)
            ->message('You have successfully item likes count.')
            ->success()
            ->generate();
    }
}
