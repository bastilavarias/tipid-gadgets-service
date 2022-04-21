<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ItemBookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemBookmarkController extends Controller
{
    public function store(Request $request)
    {
        $foundItemBookmark = ItemBookmark::where([
            'item_id' => $request->input('item_id'),
            'user_id' => Auth::id(),
        ])
            ->get()
            ->first();
        if (!empty($foundItemBookmark)) {
            $foundItemBookmark->delete();
            return customResponse()
                ->data(null)
                ->message('You have remove your bookmark in this item.')
                ->success()
                ->generate();
        }
        ItemBookmark::create([
            'item_id' => $request->input('item_id'),
            'user_id' => Auth::id(),
        ]);
        return customResponse()
            ->data(null)
            ->message('You have bookmarked this item.')
            ->success()
            ->generate();
    }
}
