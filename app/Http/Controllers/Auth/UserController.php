<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|min:3',
            'password' => 'required|min:6' ,
            'phone' => 'required|size:10',
            'title' => 'required' 

        ]);
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => bcrypt($request->password),
           
        ]);
        $user->userDetails()->create([
            'phone' => $request->phone,
            'title' => $request->title,
            'address' => $request->address,
            'image' => $request->image,
            'bio' => $request->bio
        ]);
        $accessToken = $user->createToken('My Token')->plainTextToken;
        return response()->json([
            "info" => [
                'success' => true,
                'message' => 'User created successfully',
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
            ] , 
            "body" =>[
            'user' => User::with('userDetails')->find($user->id)
       ] ] , 200);
    }
    public function login(Request $request)  {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        if (!Auth::guard('user')->attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login details'
            ], 401);
        }
    
        $user = User::where('email', $request->email)->firstOrFail();
        $accessToken = $user->createToken('My Token')->plainTextToken;
      
    
        return response()->json([
            "info" => [
                'success' => true,
                'message' => 'User loged in successfully',
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
            ] , 
            "body" =>[
            'user' => User::with('userDetails')->find($user->id)
       ] ] , 200);
    }
        

//     public function logout()
// {
//     $user = auth()->user();

//     // Revoke the access token
//     $user->token()->where('name', 'auth_token')->delete();

//     // Clear the token from the user's session
//     session()->forget('access_token');

//     return response()->json([
//         'message' => 'Successfully logged out'
//     ]);
// }

}
