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
                ->message('You have removed your bookmark in this item.')
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

    public function check($itemID)
    {
        $foundItemBookmark = ItemBookmark::where([
            'item_id' => $itemID,
            'user_id' => Auth::id(),
        ])
            ->get()
            ->first();
        $isBookmarked = !empty($foundItemBookmark);
        return customResponse()
            ->data($isBookmarked)
            ->message(
                'You have successfully check if the user bookmarked this item post.'
            )
            ->success()
            ->generate();
    }

    public function index(Request $request)
    {
        $sortBy = $request->sort_by ? $request->sort_by : 'created_at';
        $orderBy = $request->order_by ? $request->order_by : 'desc';
        $page = $request->page ? intval($request->page) : 1;
        $perPage = $request->per_page ? intval($request->per_page) : 10;
        $userID = $request->user_id ? $request->user_id : null;
        $query = ItemBookmark::query();
        if (!empty($userID)) {
            $query = $query->where('user_id', '=', $userID);
        }
        $query
            ->with(['item'])
            ->orderBy($sortBy, $orderBy);
        $bookmarks = $query->paginate($perPage, ['*'], 'page', $page);
        return customResponse()
            ->data($bookmarks)
            ->message('You have successfully get user item bookmarks.')
            ->success()
            ->generate();
    }
}
