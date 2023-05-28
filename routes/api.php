<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Feed\FeedController;

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

Route::get('/test',function(){
    return response([
        'message' => 'API is working'
    ]);  
});

Route::post('/register',[AuthenticationController::class,'register']);
Route::post('/login',[AuthenticationController::class,'login']);

Route::get('/feeds',[FeedController::class,'fetch'])->middleware('auth:sanctum');
Route::post('/feed/store',[FeedController::class,'store'])->middleware('auth:sanctum');
Route::post('/feed/like/{feed_id}',[FeedController::class,'likePost'])->middleware('auth:sanctum');
Route::post('/feed/comment/{feed_id}',[FeedController::class,'comment'])->middleware('auth:sanctum');
Route::get('/feed/comments/{feed_id}',[FeedController::class,'getComment'])->middleware('auth:sanctum');