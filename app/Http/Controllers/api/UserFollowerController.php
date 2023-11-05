<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserFollower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFollowerController extends Controller
{
    public function store(Request $request)
    {
        $userID = $request->input('user_id');
        $follow = UserFollower::where([
            'follower_id' => Auth::id(),
            'user_id' => $userID,
        ])
            ->get()
            ->first();

        if (!empty($follow)) {
            $user = User::find($follow->user_id);
            $follow->delete();
            return customResponse()
                ->data(null)
                ->message('You unfollowed ' . $user->username . '.')
                ->success()
                ->generate();
        }
        $follow = UserFollower::create([
            'follower_id' => Auth::id(),
            'user_id' => $userID,
        ]);
        $user = User::find($follow->user_id);
        return customResponse()
            ->data(null)
            ->message('You followed ' . $user->username . '.')
            ->success()
            ->generate();
    }

    public function check($userID)
    {
        $follow = UserFollower::where([
            'follower_id' => Auth::id(),
            'user_id' => $userID,
        ])
            ->get()
            ->first();

        $isFollowed = !empty($follow);
        return customResponse()
            ->data($isFollowed)
            ->message('You have successfully check if the user followed this user.')
            ->success()
            ->generate();
    }

    public function index(Request $request)
    {
        $page = $request->page ? intval($request->page) : 1;
        $perPage = $request->per_page ? intval($request->per_page) : 10;
        $filterBy = $request->filter_by ? $request->filter_by : null;
        $userID = $request->user_id ? $request->user_id : null;
        $query = UserFollower::query();
        if (!empty($filterBy)) {
            if ($filterBy == 'follower') {
                $query = $query->where('user_id', $userID);
            } elseif ($filterBy == 'following') {
                $query = $query->where('follower_id', '=', $userID);
            }
        }
        $query
            ->with(['user', 'follower'])
            ->orderBy('created_at', 'desc');
        $users = $query->paginate($perPage, ['*'], 'page', $page);
        return customResponse()
            ->data($users)
            ->message('You have successfully get users.')
            ->success()
            ->generate();
    }
}
