<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\userDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($validatedData)) { 
            $token = $request->user()->createToken('auth_token')->plainTextToken;           
            return response()->json(['success' => true, 'message' => 'Login successful' , 'token' => $token]);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid credentials']);
        }
    }

    public function register(request $request) {
             $userData = $request->validate([
                'name' => 'required|min:3' , 
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ]);

            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt($userData['password']),
            ]);
            $userDetail = $this->registerUserDetails($request , $user->id);
            $user->save();
            return response()->json(['success' => true, 'user' => $user]);
    }

    public function registerUserDetails(request $request , $userId) {
        $request->validate([
            'phone_number' => 'required|size:10',
            'location' => 'required',
            'title' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
 
        $userDetails = userDetail::create([
            'user_id' => $userId,
            'phone_number' => $request->phone_number,
            'location' => $request->location,
            'category' => $request->category,
        ]);
 
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $userDetails->photo = $name;
        }
        $token = $userDetails->createToken('company-access-token')->plainTextToken;
 
 
        $userDetails->save();
        return response()->json(['success' => true , 'companyDetails' => $userDetails , 'token' => $token]);
    }
}