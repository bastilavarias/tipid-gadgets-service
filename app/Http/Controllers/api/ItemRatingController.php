<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemRatingController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ? intval($request->page) : 1;
        $perPage = $request->per_page ? intval($request->per_page) : 10;
        $userID = $request->user_id ? $request->user_id : null;

        // finish this
    }
}
