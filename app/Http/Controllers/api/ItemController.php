<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\item\StoreDraftRequest;
use App\Http\Requests\item\StoreItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function store(StoreItemRequest $request)
    {
        $createdItem = Item::create([
            'item_section_id' => $request->input('item_section_id'),
            'name' => $request->input('name'),
            'item_category_id' => $request->input('item_category_id'),
            'price' => $request->input('price'),
            'user_id' => Auth::id(),
            'item_condition_id' => $request->input('item_condition_id'),
            'item_warranty_id' => $request->input('item_warranty_id'),
            'description' => $request->input('description'),
            'is_draft' => 0,
        ]);

        Item::where('id', $createdItem->id)->update([
            'slug' => Str::of($createdItem->name)->snake() . '_' . $createdItem->id,
        ]);

        $foundItem = Item::where('id', $createdItem->id)
            ->get()
            ->first();

        return customResponse()
            ->data($foundItem)
            ->message('You have successfully posted an item.')
            ->success()
            ->generate();
    }

    public function getDrafts()
    {
        $items = Item::where([
            'user_id' => Auth::id(),
            'is_draft' => 1,
        ])->get();
        return customResponse()
            ->data($items)
            ->message('You have successfully get drafted posts.')
            ->success()
            ->generate();
    }

    public function storeDraft(StoreDraftRequest $request)
    {
        if (empty($request->input('id'))) {
            return customResponse()
                ->data(null)
                ->message('Draft not found.')
                ->failed(404)
                ->generate();
        }

        $itemID = $request->input('id');
        $foundItem = Item::where('id', $itemID)
            ->get()
            ->first();

        if (!$foundItem->is_draft) {
            return customResponse()
                ->data(null)
                ->message('You cant save a draft item if already posted.')
                ->failed()
                ->generate();
        }

        $updatedItem = Item::where('id', $itemID)->update([
            'id' => $itemID,
            'item_section_id' => $request->input('item_section_id'),
            'name' => $request->input('name'),
            'item_category_id' => $request->input('item_category_id'),
            'price' => $request->input('price'),
            'item_condition_id' => $request->input('item_condition_id'),
            'item_warranty_id' => $request->input('item_warranty_id'),
            'description' => $request->input('description'),
            'is_draft' => 1,
        ]);

        $foundItem = Item::where('id', $updatedItem)
            ->get()
            ->first();

        return customResponse()
            ->data($foundItem)
            ->message('You have successfully updated drafted item.')
            ->success()
            ->generate();
    }
}
