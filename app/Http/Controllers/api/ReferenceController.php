<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use App\Models\ItemCondition;
use App\Models\ItemSection;
use App\Models\ItemWarranty;
use App\Models\Location;
use App\Models\SearchType;
use App\Models\TopicSection;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    public function locations()
    {
        $data = Location::all();
        return customResponse()
            ->data($data)
            ->message('Get locations successful.')
            ->success()
            ->generate();
    }

    public function itemSections()
    {
        $data = ItemSection::all();
        return customResponse()
            ->data($data)
            ->message('Get item sections successful.')
            ->success()
            ->generate();
    }

    public function itemCategories()
    {
        $data = ItemCategory::all();
        return customResponse()
            ->data($data)
            ->message('Get item categories successful.')
            ->success()
            ->generate();
    }

    public function itemConditions()
    {
        $data = ItemCondition::all();
        return customResponse()
            ->data($data)
            ->message('Get item conditions successful.')
            ->success()
            ->generate();
    }

    public function itemWarranties()
    {
        $data = ItemWarranty::all();
        return customResponse()
            ->data($data)
            ->message('Get item warranties successful.')
            ->success()
            ->generate();
    }

    public function topicSections()
    {
        $data = TopicSection::all();
        return customResponse()
            ->data($data)
            ->message('Get topic sections successful.')
            ->success()
            ->generate();
    }

    public function searchTypes()
    {
        $data = SearchType::all();
        return customResponse()
            ->data($data)
            ->message('Get search types successful.')
            ->success()
            ->generate();
    }
}
