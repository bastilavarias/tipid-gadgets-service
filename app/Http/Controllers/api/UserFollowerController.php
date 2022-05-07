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
}
