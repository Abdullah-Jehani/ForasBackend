<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\PersonalAccessTokenResult;

class CompanyController extends Controller
{
    protected function guard()
    {
        return Auth::guard('company');
    }
    public function register(Request $request) {
        
            
        $request->validate([
            'email' => 'required|email|unique:companies',
            'name' => 'required|min:3',
            'password' => 'required|min:6' ,
            'phone' => 'required|size:10',
            'category' => 'required',
            'address' => 'required',
            'logo' => 'required',
        ]);

        $company = Company::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);
        $company->CompanyDetails()->create([
            'phone' => $request->phone,
            'category' => $request->category,
            'address' => $request->address,
            'logo' => $request->logo,
            'bio' => $request->bio
        ]);
        $accessToken = $company->createToken('My Token')->accessToken;
        return response()->json([
            "info" => [
                'success' => true,
                'message' => 'Company created successfully',
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
            ] , 
            "body" =>[
            'user' => Company::with('CompanyDetails')->find($company->id)

       ] ] , 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        if (!Auth::guard('company')->attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login details'
            ], 401);
        }
    
        $company = Company::where('email', $request->email)->firstOrFail();
        $accessToken = $company->createToken('My Token')->accessToken;
      
    
        return response()->json([
            "info" => [
                'success' => true,
                'message' => 'company loged in successfully',
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
            ] , 
            "body" =>[
            'user' => Company::with('CompanyDetails')->find($company->id)
       ] ] , 200);
    }


    


    
}
