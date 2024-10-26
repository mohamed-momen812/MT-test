<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


Route::middleware([
    'api',
    InitializeTenancyBydomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::prefix('api/v1')->group(function () {
            // Public routes
            // Auth routes
            Route::post('/register', [AuthController::class, 'register']);
            Route::post('/login', [AuthController::class, 'login']);

            // Protected routes
            Route::group(['middleware' => ['auth:sanctum']], function () {
                // Auth routes
                Route::post('/logout', [AuthController::class, 'logout']);
                Route::get('/user', function (Request $request) {
                    return $request->user();
                    });

                // Logic routes
                Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
                Route::get('/premium-content', function () {
                    dd('Premium content');
                    })->middleware('subscription');

            });
        });
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
});
