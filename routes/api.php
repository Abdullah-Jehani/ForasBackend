<?php

use App\Http\Controllers\Auth\CompanyController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/register' , [UserController::class , 'register']);
Route::post('/user/login' , [UserController::class , 'login']);
Route::post('/user/logout' , [UserController::class , 'logout']);

Route::post('/company/register' , [CompanyController::class , 'register']);
Route::post('/company/login' , [CompanyController::class , 'login']);
Route::post('/company/logout' , [CompanyController::class , 'logout']);



