<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // validate
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_no' => 'required',
            'password' => 'required|confirmed',
        ]);

        // create and store
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;
        $user->password = bcrypt($request->password);

        $user->save();

        return response()->json([
            'status' => 1,
            "message" => "User created successfully"
        ], 201);
    }

    public function login(Request $request)
    {
        // validate
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // verify user + token
        if (!$token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid credentials'
            ]);
        }

        return response()->json([
            'status' => 1,
            'message' => 'Logged in successfully',
            'access_token' => $token
        ]);
    }

    public function profile()
    {
        $user = auth()->user();
        return response()->json([
            'status' => 1,
            'message' => "User profile data",
            "data" => $user
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'status' => 1,
            'message' => 'User logged out successfully'
        ]);
    }
}
