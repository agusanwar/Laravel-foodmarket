<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\MidtransController;
use App\Http\Controllers\API\TransactionController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::GET('user', [UserController::class, 'fetch']);
    Route::POST('user', [UserController::class, 'updateProfile']);
    Route::POST('user/photo', [UserController::class, 'updatePhoto']);

    Route::GET('transaction', [TransactionController::class, 'all']);
    Route::POST('transaction/{id}', [TransactionController::class, 'update']);
    
    Route::POST('checkout', [TransactionController::class, 'checkout']);

    Route::POST('logout', [UserController::class, 'logout']);
});

Route::POST('register', [UserController::class, 'register']);
Route::POST('sign',[UserController::class,'sign']);
Route::POST('login', [UserController::class, 'login']);

Route::GET('food', [FoodController::class, 'all']);

Route::POST('product', [ProductController::class, 'create']);
Route::GET('product', [ProductController::class, 'show']);
// Route::POST('product', [ProductController::class, 'edit']);
Route::DELETE('product/{id}', [ProductController::class, 'delete']);
Route::POST('product/{id}', [ProductController::class, 'update']);
Route::POST('product/{id}', [ProductController::class, 'all']);

Route::POST('midtrans/callback', [MidtransController::class, 'callback']);