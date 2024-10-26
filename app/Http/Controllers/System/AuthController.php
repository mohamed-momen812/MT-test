<?php

namespace App\Http\Controllers\System;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function register(StoreUserRequest $request)
    {
        // $request->validated($request->all()); not needed

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password) // hash password before store in db
        ]);

        return $this->success([
            'user' => new UserResource($user),
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken // plainTextToken for send plain Text Token to client not encrepted one
        ], "User Created success");
    }
    public function login(LoginUserRequest $request)
    {

        // Laravel automatically hashes the password and compares it to the hashed password stored in the database.
        if(!Auth::attempt($request->only(['email', 'password']))){
            // if Credentials failed
            return $this->error('', 'Credentials do not match', 401);
        }
        // now can access to authenticated user via Auth::user

        $user = User::where('email', $request->email)->first();
        return $this->success([
            'user' => new UserResource($user),
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken // plainTextToken for send plain Text Token to client not encrepted one
        ], "User Loged success");

    }
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have successfully been logged out and your token has been deleted'
        ]);
    }
}
