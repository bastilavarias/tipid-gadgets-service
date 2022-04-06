<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    public function locations()
    {
        $locations = Location::all();

        return customResponse()
            ->data($locations)
            ->message('Get locations successfull.')
            ->success()
            ->generate();
    }
}
