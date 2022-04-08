<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\authentication\LoginRequest;
use App\Http\Requests\authentication\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'location' => $request->input('location'),
            'password' => bcrypt($request->input('password')),
        ]);

        return customResponse()
            ->data($user)
            ->message('User registration successful.')
            ->success()
            ->generate();
    }

    public function githubAuthentication(Request $request)
    {
        $githubAccessToken = $this->authenticateGithubCode($request->input('code'));
        if (empty($githubAccessToken)) {
            return customResponse()
                ->data(null)
                ->message('Invalid GitHub code.')
                ->failed()
                ->generate();
        }

        $githubUser = $this->getGithubUser($githubAccessToken);
        $foundUser = User::where('username', '=', $githubUser->login)
            ->orWhere('email', '=', $githubUser->email)
            ->first();

        if ($foundUser !== null) {
            return customResponse()
                ->data([
                    'access_token' => $foundUser->createToken('authToken')->accessToken,
                    'user' => $foundUser,
                ])
                ->message('GitHub authentication success.')
                ->success()
                ->generate();
        }

        $createdUser = User::create([
            'name' => $githubUser->name,
            'username' => $githubUser->login,
            'email' => $githubUser->email,
            'avatar' => $githubUser->avatar_url,
        ]);

        return customResponse()
            ->data([
                'access_token' => $createdUser->createToken('authToken')->accessToken,
                'user' => $createdUser,
            ])
            ->message('GitHub authentication success.')
            ->success()
            ->generate();
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->all())) {
            return customResponse()
                ->data(null)
                ->message('Invalid credentials.')
                ->unathorized()
                ->generate();
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        $user = User::where('id', Auth::id())
            ->get()
            ->first();

        return customResponse()
            ->data([
                'user' => $user,
                'access_token' => $accessToken,
            ])
            ->message('You have successfully logged in.')
            ->success()
            ->generate();
    }

    public function authenticateGithubCode($code)
    {
        $response = Http::post('https://github.com/login/oauth/access_token', [
            'client_id' => env('GITHUB_CLIENT_ID'),
            'client_secret' => env('GITHUB_CLIENT_SECRET'),
            'code' => $code,
        ]);

        $responseBody = $response->body();
        $explodedString = explode('=', $responseBody);
        return $explodedString[0] == 'access_token'
            ? explode('&', $explodedString[1])[0] // access token
            : null;
    }

    public function getGithubUser($accessToken)
    {
        $response = Http::withToken($accessToken)->get('https://api.github.com/user');
        return json_decode($response->body());
    }
}
