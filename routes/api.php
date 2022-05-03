<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // 인증 완료 되었을 때
    Route::middleware('auth:api')->group(function() {
//        Route::get('user', [UserController::class, 'details']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// User
Route::group(['prefix'=>'user'], function () {
    Route::get('info', [UserController::class, 'details']);
    Route::post('{id}', [UserController::class, 'update']);
    Route::delete('delete/{id}', [UserController::class, 'delete']);
});

// Post
Route::resource('posts', PostController::class);

// Comment
Route::resource('posts.comments', CommentController::class)->shallow();
