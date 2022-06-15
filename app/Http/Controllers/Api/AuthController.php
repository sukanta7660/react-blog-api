<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required | string | max:255',
            'email' => 'required | email | max:191 | unique:users',
            'password' => 'required | min:6 | string | confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('MyToken')->accessToken;

        return response()->json([
            'user' => $user,
            'message' => 'Registration Successful'
        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $auth = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if ($auth) {
            $user = Auth::user();
            $token = $user->createToken('MyToken')->accessToken;

            $data = [
              'user' => $user,
              'token' => $token->token,
              'message' =>  'Logged in successfully'
            ];

            return Helper::sendResponse($data, 'Logged in successfully', true);
        }

        return Helper::sendResponse([], 'Credential not matched', false, 400);
    }

    public function logout()
    {
        Auth::logout();
    }
}
