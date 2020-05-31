<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\User as UserResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => 'The login request contained invalid data', 'errors' => $validator->messages()], 422);
        }

        if (Auth::attempt($credentials)) {
            return response(new UserResource(Auth::user()), 200);
        }

        return response(['message' => 'We couldn’t find an account matching those details'], 404);
    }
}