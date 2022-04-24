<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemBookmark;
use App\Models\ItemLike;
use App\Models\ItemView;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function showItem($id)
    {
        $item = Item::find($id);
        if (!empty($item)) {
            $insight = [
                'reach' => $item->views->count(),
                'unique_viewers' => $item->views
                    ->filter(function ($q) {
                        return !empty($q->user_id);
                    })
                    ->count(),
                'likes' => $item->likes->count(),
                'bookmarks' => $item->bookmarks->count(),
            ];
            return customResponse()
                ->data($insight)
                ->message('You have successfully got item post insight.')
                ->success()
                ->generate();
        }
        return customResponse()
            ->data(null)
            ->message('Item not found.')
            ->notFound()
            ->generate();
    }

    public function showTopic($id)
    {
        $topic = Item::find($id);
        if (!empty($topic)) {
            $insight = [
                'reach' => $topic->views->count(),
                'unique_viewers' => $topic->views
                    ->filter(function ($q) {
                        return !empty($q->user_id);
                    })
                    ->count(),
                'likes' => $topic->likes->count(),
                'bookmarks' => $topic->bookmarks->count(),
            ];
            return customResponse()
                ->data($insight)
                ->message('You have successfully got topic post insight.')
                ->success()
                ->generate();
        }
        return customResponse()
            ->data(null)
            ->message('Topic not found.')
            ->notFound()
            ->generate();
    }
}
