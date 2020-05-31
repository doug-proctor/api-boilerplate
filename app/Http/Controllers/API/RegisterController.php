<?php

namespace App\Http\Controllers\API;

use App\User;

use App\Http\Resources\User as UserResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function register(Request $request)
    {
        $credentials = $request->only('name', 'email', 'password');

        $validator = Validator::make($credentials, [
            'name' => 'required|max:64',
            'email' => 'required|unique:users|email|max:128',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => 'The register request contained invalid data', 'errors' => $validator->messages()], 422);
        }

        $attributes = $credentials;
        $attributes['password'] = Hash::make($attributes['password']);
        User::create($attributes);

        if (Auth::attempt($credentials)) {
            return response(new UserResource(Auth::user()), 200);
        }

        return response(['message' => 'Something went wrong. Please try again. '], 500);
    }
}