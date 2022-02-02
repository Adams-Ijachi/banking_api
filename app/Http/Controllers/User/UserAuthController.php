<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// UserRegisterationRequest
use App\Http\Requests\{
    UserRegisterationRequest,
    UserLoginRequest
};

use App\Http\Resources\UserResource;

use App\Models\{
    User
};


use Auth;
use Hash;


class UserAuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \App\Http\Requests\UserRegisterationRequest  $request
     * @return Illuminate\Http\Response
    */
    public function register(UserRegisterationRequest $request){

        $validated = $request->validated();
        $user = User::create($validated);
        return response()->json(['message' => 'User registered successfully.'],200);

    }

    /**
     * Login a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Illuminate\Http\Response
     * 
    */

    public function login(UserLoginRequest $request){

        $validated = $request->validated();
        $user = User::where('email',$validated['email'])->first();

        
        if(!$user ||!Hash::check($validated['password'], $user->password)) {
            return response([
                'message' => 'Invalid Credentials'
            ], 401);
        };


        $token = $user->createToken('auth_token', ['server:user'])->plainTextToken;
        return (new UserResource($user))->additional(['meta' => [ 'token' =>$token]]);

    }
    
    public function logout()
    {
        # code...
        Auth::user()->tokens()->delete();
        return response()->json(['message'=>'Logged out sucessfully'], 200);

    }

    
}
