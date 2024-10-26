<?php

use App\Http\Controllers\System\AuthController;
use App\Http\Controllers\System\SubdomainController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::prefix('v1')->group(function () {
            Route::post('/admin', [AuthController::class, 'register'])->middleware('can:is-admin');
            Route::post('subdomain', [SubdomainController::class, 'store'])->middleware(['auth:sanctum', 'can:is-admin']);
        });
    });
}






