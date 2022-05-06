<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\item\StoreDraftRequest;
use App\Http\Requests\item\StoreItemRequest;
use App\Models\Item;
use App\Models\ItemDescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function store(StoreItemRequest $request)
    {
        if (!empty($request->input('id'))) {
            $itemID = $request->input('id');
            $foundItem = Item::where('id', $itemID)
                ->get()
                ->first();
            Item::where('id', $itemID)->update([
                'user_id' => Auth::id(),
                'id' => $itemID,
                'item_section_id' => $request->input('item_section_id'),
                'name' => $request->input('name'),
                'item_category_id' => $request->input('item_category_id'),
                'price' => $request->input('price'),
                'item_condition_id' => $request->input('item_condition_id'),
                'item_warranty_id' => $request->input('item_warranty_id'),
                'is_draft' => 0,
            ]);
            ItemDescription::where('id', $foundItem->item_description_id)->update([
                'content' => $request->input('description'),
            ]);
            return customResponse()
                ->data($foundItem)
                ->message('You have successfully posted an item.')
                ->success()
                ->generate();
        }

        $createdDescription = ItemDescription::create([
            'content' => $request->input('description'),
        ]);
        $createdItem = Item::create([
            'user_id' => Auth::id(),
            'item_section_id' => $request->input('item_section_id'),
            'name' => $request->input('name'),
            'item_category_id' => $request->input('item_category_id'),
            'price' => $request->input('price'),
            'item_condition_id' => $request->input('item_condition_id'),
            'item_warranty_id' => $request->input('item_warranty_id'),
            'item_description_id' => $createdDescription->id,
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
        $items = Item::with(['description'])
            ->where([
                'user_id' => Auth::id(),
                'is_draft' => 1,
            ])
            ->get();
        return customResponse()
            ->data($items)
            ->message('You have successfully get drafted posts.')
            ->success()
            ->generate();
    }

    public function storeDraft(StoreDraftRequest $request)
    {
        if (empty($request->input('id'))) {
            $createdDescription = ItemDescription::create([
                'content' => $request->input('description'),
            ]);
            $createdItem = Item::create([
                'user_id' => Auth::id(),
                'item_section_id' => $request->input('item_section_id'),
                'name' => $request->input('name'),
                'item_category_id' => $request->input('item_category_id'),
                'price' => $request->input('price'),
                'item_condition_id' => $request->input('item_condition_id'),
                'item_warranty_id' => $request->input('item_warranty_id'),
                'item_description_id' => $createdDescription->id,
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
        Item::where('id', $itemID)->update([
            'user_id' => Auth::id(),
            'id' => $itemID,
            'item_section_id' => $request->input('item_section_id'),
            'name' => $request->input('name'),
            'item_category_id' => $request->input('item_category_id'),
            'price' => $request->input('price'),
            'item_condition_id' => $request->input('item_condition_id'),
            'item_warranty_id' => $request->input('item_warranty_id'),
            'is_draft' => 1,
        ]);
        ItemDescription::where('id', $foundItem->item_description_id)->update([
            'content' => $request->input('description'),
        ]);
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
        $search = $request->search ? $request->search : null;
        $categoryID = $request->category_id ? $request->category_id : null;
        $conditionID = $request->condition_id ? $request->condition_id : null;
        $warrantyID = $request->warranty_id ? $request->warranty_id : null;
        $minimumPrice = $request->minimum_price ? $request->minimum_price : null;
        $maximumPrice = $request->maximum_price ? $request->maximum_price : null;
        $userID = $request->user_id ? $request->user_id : null;
        $query = Item::query();
        if (!empty($filterBy)) {
            if ($filterBy == 'items_for_sale') {
                $itemForSaleID = 1;
                $query = $query->where('item_section_id', '=', $itemForSaleID);
            } elseif ($filterBy == 'want_to_buys') {
                $wantToBuyID = 2;
                $query = $query->where('item_section_id', '=', $wantToBuyID);
            }
        }
        if (!empty($userID)) {
            $query = $query->where('user_id', '=', $userID);
        }
        if (!empty($search)) {
            $query = $query->where('name', 'LIKE', '%' . $search . '%');
        }
        if (!empty($categoryID)) {
            $query = $query->where('item_category_id', '=', $categoryID);
        }
        if (!empty($conditionID)) {
            $query = $query->where('item_condition_id', '=', $conditionID);
        }
        if (!empty($warrantyID)) {
            $query = $query->where('item_warranty_id', '=', $warrantyID);
        }
        if (!empty($minimumPrice) || !empty($maximumPrice)) {
            if (!empty($minimumPrice) && !empty($maximumPrice)) {
                $query = $query->whereBetween('price', [$minimumPrice, $maximumPrice]);
            } elseif (!empty($minimumPrice) && empty($maximumPrice)) {
                info($minimumPrice);
                $query = $query->where('price', '>=', $minimumPrice);
            } elseif (empty($minimumPrice) && !empty($maximumPrice)) {
                $query = $query->where('price', '<=', $maximumPrice);
            }
        }
        $query
            ->with(['user', 'category'])
            ->where('is_draft', '=', 0)
            ->orderBy($sortBy, $orderBy)
            ->paginate($perPage, ['*'], 'page', $page);
        $items = $query->get();
        return customResponse()
            ->data($items)
            ->message('You have successfully get item posts.')
            ->success()
            ->generate();
    }

    public function getImages($id)
    {
        $item = Item::with(['description'])
            ->where('id', $id)
            ->get()
            ->first();
        if (empty($item)) {
            return customResponse()
                ->data(null)
                ->message('Item not found.')
                ->notFound()
                ->generate();
        }
        $content = $item->description->content;
        if (empty($content)) {
            return customResponse()
                ->data([])
                ->message('You have successfully item images.')
                ->success()
                ->generate();
        }
        $images = $this->extractBase64Images($content);
        return customResponse()
            ->data($images)
            ->message('You have successfully item images.')
            ->success()
            ->generate();
    }

    public function extractBase64Images($text)
    {
        $pattern = '#data:image/(gif|png|jpeg);base64,([\w=+/]++)#';
        return preg_match_all($pattern, $text, $output) ? $output[0] : [];
    }

    public function show($slug)
    {
        $item = Item::with([
            'description',
            'user',
            'section',
            'category',
            'condition',
            'warranty',
        ])
            ->where('slug', $slug)
            ->get()
            ->first();
        if (empty($item)) {
            return customResponse()
                ->data(null)
                ->message('Item not found.')
                ->notFound()
                ->generate();
        }

        $item->user->setAttribute(
            'positive_ratings_percentage',
            $this->getPercentage(
                $item->user->positive_ratings_count,
                $item->user->ratings_count
            )
        );
        $item->user->setAttribute(
            'negative_ratings_percentage',
            $this->getPercentage(
                $item->user->negative_ratings_count,
                $item->user->ratings_count
            )
        );
        return customResponse()
            ->data($item)
            ->message('You have successfully get item.')
            ->success()
            ->generate();
    }

    private function getPercentage($value, $total)
    {
        return ($value / $total) * 1;
    }
}
