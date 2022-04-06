<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\authentication\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        return customResponse()
            ->data(null)
            ->message('User registration successful.')
            ->success()
            ->generate();
    }
}
