<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\item\StoreItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;
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
            'item_condition_id' => $request->input('item_condition_id'),
            'item_warranty_id' => $request->input('item_warranty_id'),
            'description' => $request->input('description'),
            'is_draft' => $request->input('is_draft'),
        ]);

        Item::where('id', $createdItem->id)->update([
            'slug' => Str::of($createdItem->name)->snake() . '_' . $createdItem->id,
        ]);

        $foundItem = Item::where('id', $createdItem)
            ->get()
            ->first();

        return customResponse()
            ->data($foundItem)
            ->message('You have successfully posted an item.')
            ->success()
            ->generate();
    }
}
