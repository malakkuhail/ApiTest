<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


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
Route::get('users',[UserController::class,'getUsers']);
Route::group(['middleware'=> 'guest:user-api'],function (){
    Route::get('user',[UserController::class,'user']);
    Route::post('user/store',[UserController::class,'store']);
    Route::post('user/destroy',[UserController::class,'destroy']);
    Route::post('user/edit/{id}',[UserController::class,'update']);
    Route::post('user/login',[UserController::class,'login']);
});

