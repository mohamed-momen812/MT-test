<?php

use App\Http\Controllers\AccessTokenController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

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



/**
 * to do 
 * #############
 * Error handling
 * Handle expiration time for plans
 * Handle Authentication in good way
 * add versions
 * add multi-tenancy
 */

Fortify::ignoreRoutes();

Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('auth/access-tokens', [AccessTokenController::class, 'store'])
        ->middleware('guest:sanctum'); // should be guest to access this routes

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->middleware('auth:sanctum');

Route::get('/premium-content', function () {
    dd('Premium content');
})->middleware(['auth:sanctum', 'subscription']);