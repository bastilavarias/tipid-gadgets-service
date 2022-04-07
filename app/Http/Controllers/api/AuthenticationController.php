<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\authentication\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    public function githubRegistration(Request $request)
    {
        $githubAuthResponse = Http::withHeaders(['Content: application/json'])->post(
            'https://github.com/login/oauth/access_token',
            [
                'client_id' => env('GITHUB_CLIENT_ID'),
                'client_secret' => env('GITHUB_CLIENT_SECRET'),
                'code' => $request->input('code'),
            ]
        );

        if ($githubAuthResponse->ok()) {
            return customResponse()
                ->data($githubAuthResponse->json())
                ->message('GitHub registration success.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('GitHub registration failed.')
            ->failed()
            ->generate();
    }
}
