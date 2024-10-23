<?php

namespace App\Providers;

use App\Services\PaymentServices\paddlePaymentService;
use App\Services\PaymentServices\stripePaymentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the PaymentService to the container
        $this->app->bind('PaymentService', function ($app) {
            // return new stripePaymentService();
            return new paddlePaymentService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
