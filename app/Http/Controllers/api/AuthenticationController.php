<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\authentication\RegisterRequest;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        info($request);
        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'location' => $request->input('location'),
            'password' => $request->input('password'),
        ]);
        return customResponse()
            ->data($user)
            ->message('User registration successful.')
            ->success()
            ->generate();
    }
}
