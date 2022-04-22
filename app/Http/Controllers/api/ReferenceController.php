<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use App\Models\ItemCondition;
use App\Models\ItemSection;
use App\Models\ItemWarranty;
use App\Models\Location;
use App\Models\TopicSection;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    public function locations()
    {
        $locations = Location::all();
        return customResponse()
            ->data($locations)
            ->message('Get locations successful.')
            ->success()
            ->generate();
    }

    public function itemSections()
    {
        $sections = ItemSection::all();
        return customResponse()
            ->data($sections)
            ->message('Get item sections successful.')
            ->success()
            ->generate();
    }

    public function itemCategories()
    {
        $categories = ItemCategory::all();
        return customResponse()
            ->data($categories)
            ->message('Get item categories successful.')
            ->success()
            ->generate();
    }

    public function itemConditions()
    {
        $conditions = ItemCondition::all();
        return customResponse()
            ->data($conditions)
            ->message('Get item conditions successful.')
            ->success()
            ->generate();
    }

    public function itemWarranties()
    {
        $warranties = ItemWarranty::all();
        return customResponse()
            ->data($warranties)
            ->message('Get item warranties successful.')
            ->success()
            ->generate();
    }

    public function topicSections()
    {
        $sections = TopicSection::all();
        return customResponse()
            ->data($sections)
            ->message('Get topic sections successful.')
            ->success()
            ->generate();
    }
}
