<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Tymon\JWTAuth\JWT;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        print("In Registering ........");
        $validate = $request->validate(
            [
                'name'=>'required|string|max:255',
                'email'=>'required|string|email|unique:users',
                'password'=>'required|min:8|confirmed'
            ]
        );
        $validate['password'] = Hash::make($validate['password']);
        $user = User::create($validate);
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user', 'token'), 201);
    }

    public function login(Request $request) {
        print("In Logging In User");
        $credentials = $request->validate(
            [
                'email'=>'',
                'password'=>''
            ]
        );
        
        if ( $token =! JWTAuth::attempt($credentials)){
            return response()->json([
                'error'=>'Invalid Credentials'
            ]);
        }

        $user = Auth::user();
        return response()->json(compact('token', 'user'));
    }
}
