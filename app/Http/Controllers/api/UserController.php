<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->sort_by ? $request->sort_by : 'created_at';
        $orderBy = $request->order_by ? $request->order_by : 'desc';
        $page = $request->page ? intval($request->page) : 1;
        $perPage = $request->per_page ? intval($request->per_page) : 10;
        $search = $request->search ? $request->search : null;
        $location = $request->location ? $request->location : null;
        $query = User::query();
        if (!empty($search)) {
            $query = $query
                ->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('username', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%');
        }
        if (!empty($location)) {
            $query = $query->where('location', '=', $location);
        }
        $query->orderBy($sortBy, $orderBy)->paginate($perPage, ['*'], 'page', $page);
        $topics = $query->get();
        return customResponse()
            ->data($topics)
            ->message('You have successfully get users.')
            ->success()
            ->generate();
    }
}