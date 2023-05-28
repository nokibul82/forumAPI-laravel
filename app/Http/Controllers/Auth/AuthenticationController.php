<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseStatusCodeSame;

use function Psy\debug;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validated();
        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $user = User::create($userData);
        $token = $user->createToken('forumapp')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $request->validated();
        $user = User::where('username', $request->username)->first();
        if (!$user || Hash::check(Hash::make($request->password), $user->password)) {
            return response([
                'message' => 'Invalid credentials. please provide correct username and password'
            ], 422);
        }
        $token = $user->createToken('forumapp')->plainTextToken;
        return response([
            'user' => $user,
            'token' => $token
        ], 200);
    }
}
