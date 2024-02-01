<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\CompanyDetail; // Corrected model name
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
   // authentication for company 

   public function login(Request $request)
   {
       $request->validate([
           'email' => 'required|email',
           'password' => 'required|min:6',
       ]);
   
       if (Auth::guard('company')->attempt(['email' => $request->email, 'password' => $request->password])) {
           $company = Company::where('email', $request->email)->firstOrFail();
           $token = $company->createToken('company-access-token')->plainTextToken;
   
           return response()->json(['success' => true, 'token' => $token]);
       }
   
       throw ValidationException::withMessages([
           'email' => ['The provided credentials are incorrect.'],
       ]);
   
   }

   public function register(Request $request)
   {
       $request->validate([
           'email' => 'required|email',
           'company_name' => 'required|min:3',
           'password' => 'required|min:6',
            
       ]);
       try {
           $company = Company::create([
               'email' => $request->email,
               'company_name' => $request->company_name,
               'password' => bcrypt($request->password)
           ]);
           $company->save();
           $u = $this->registerCompanyDetails($request, $company->id);
           echo $company->id;
   
           return response()->json([
               'success' => true,
               'company_id' => $company->id,
           ]);
       } catch (\Exception $e) {
           return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
       }
   }
   
   public function registerCompanyDetails(Request $request, $companyId)
   {
       $request->validate([
           'phone_number' => 'nullable|size:10',
           'location' => 'nullable',
           'category' => 'nullable',
           'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
       ]);

       $companyDetails = CompanyDetail::create([ // Corrected model name
           'company_id' => $companyId,
           'phone_number' => $request->phone_number,
           'location' => $request->location,
           'category' => $request->category,
       ]);

       if ($request->hasFile('logo')) {
           $image = $request->file('logo');
           $name = time().'.'.$image->getClientOriginalExtension();
           $destinationPath = public_path('/images');
           $image->move($destinationPath, $name);
           $companyDetails->logo = $name;
       }
       $token = $companyDetails->createToken('company-access-token')->plainTextToken;


       $companyDetails->save();
       return response()->json(['success' => true , 'companyDetails' => $companyDetails , 'token' => $token]);
   }

   public function logout() {
       Auth::guard('company')->logout();
       return response()->json(['success' => true]);
   }
}
