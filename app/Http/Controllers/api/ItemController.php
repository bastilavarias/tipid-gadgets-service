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
        if (!empty($request->input('id'))) {
            $itemID = $request->input('id');
            $updatedItem = Item::where('id', $itemID)->update([
                'user_id' => Auth::id(),
                'id' => $itemID,
                'item_section_id' => $request->input('item_section_id'),
                'name' => $request->input('name'),
                'item_category_id' => $request->input('item_category_id'),
                'price' => $request->input('price'),
                'item_condition_id' => $request->input('item_condition_id'),
                'item_warranty_id' => $request->input('item_warranty_id'),
                'description' => $request->input('description'),
                'is_draft' => 0,
            ]);
            $foundItem = Item::where('id', $updatedItem)
                ->get()
                ->first();
            return customResponse()
                ->data($foundItem)
                ->message('You have successfully posted an item.')
                ->success()
                ->generate();
        }
        $createdItem = Item::create([
            'user_id' => Auth::id(),
            'item_section_id' => $request->input('item_section_id'),
            'name' => $request->input('name'),
            'item_category_id' => $request->input('item_category_id'),
            'price' => $request->input('price'),
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
            $createdItem = Item::create([
                'user_id' => Auth::id(),
                'item_section_id' => $request->input('item_section_id'),
                'name' => $request->input('name'),
                'item_category_id' => $request->input('item_category_id'),
                'price' => $request->input('price'),
                'item_condition_id' => $request->input('item_condition_id'),
                'item_warranty_id' => $request->input('item_warranty_id'),
                'description' => $request->input('description'),
                'is_draft' => 1,
            ]);
            Item::where('id', $createdItem->id)->update([
                'slug' => Str::of($createdItem->name)->snake() . '_' . $createdItem->id,
            ]);
            $foundItem = Item::where('id', $createdItem)
                ->get()
                ->first();
            return customResponse()
                ->data($foundItem)
                ->message('You have successfully created drafted item.')
                ->success()
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
            'user_id' => Auth::id(),
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

    public function deleteDraft($id)
    {
        $item = Item::where('id', $id)
            ->get()
            ->first();
        $item->delete();
        return customResponse()
            ->data(null)
            ->message('You have successfully deleted a drafted item.')
            ->success()
            ->generate();
    }

    public function index(Request $request)
    {
        $sortBy = $request->sort_by ? $request->sort_by : 'created_at';
        $orderBy = $request->order_by ? $request->order_by : 'desc';
        $page = $request->page ? intval($request->page) : 1;
        $perPage = $request->per_page ? intval($request->per_page) : 10;
        $filterBy = $request->filter_by ? $request->filter_by : null;
        $query = Item::query();
        if (!empty($filterBy)) {
            if ($filterBy == 'item_for_sale') {
                $itemForSaleID = 1;
                $query = $query->where('item_section_id', '=', $itemForSaleID);
            } elseif ($filterBy == 'want_to_buy') {
                $wantToBuyID = 2;
                $query = $query->where('item_section_id', '=', $wantToBuyID);
            }
        }
        $query
            ->with(['user', 'itemCategory'])
            ->where('is_draft', '=', 0)
            ->orderBy($sortBy, $orderBy)
            ->paginate($perPage, ['*'], 'page', $page);
        $items = $query->get()->makeHidden(['description']);
        return customResponse()
            ->data($items)
            ->message('You have successfully get item posts.')
            ->success()
            ->generate();
    }

    public function getImages($id)
    {
        $item = Item::where('id', $id)
            ->get()
            ->first();
        if (empty($item)) {
            return customResponse()
                ->data(null)
                ->message('Item not found.')
                ->notFound()
                ->generate();
        }
        $description = $item->description;
        if (empty($description)) {
            return customResponse()
                ->data([])
                ->message('You have successfully item images.')
                ->success()
                ->generate();
        }
        $images = $this->extractBase64Images($description);
        return customResponse()
            ->data($images)
            ->message('You have successfully item images.')
            ->success()
            ->generate();
    }

    public function extractBase64Images($text)
    {
        $pattern = '/(data:image\/[^;]+;base64[^"]+)/i';
        return preg_match($pattern, $text, $output) ? $output : [];
    }
}
