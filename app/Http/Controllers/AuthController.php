<?php

namespace App\Http\Controllers;

use Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'address' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('myAppToken')->plainTextToken;

        return Response::json([
            'user' => $user,
            'token' => $token,
        ],200);
    }

    public function login(Request $request)
    {
        $validation = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if(empty($user) || !Hash::check($request->password, $user->password)){
            return Response::json([
                'message' => 'Credential do not match...'
            ],200);
        }

        $token = $user->createToken('myAppToken')->plainTextToken;

        return Response::json([
            'user' => $user,
            'token' => $token
        ],200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return Response::json([
            'message' => 'Log out success...',
        ],200);
    }
}
