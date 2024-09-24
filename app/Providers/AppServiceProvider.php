<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\IMerchantRepository;
use App\Repositories\MerchantRepository;
use App\Services\MerchantService;

use App\Repositories\IPaymentRepository;
use App\Repositories\PaymentRepository;
use App\Services\PaymentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //merchants        
        $this->app->bind(IMerchantRepository::class, MerchantRepository::class);
        $this->app->bind(MerchantService::class, function ($app) {
            return new MerchantService($app->make(IMerchantRepository::class));
        });
        
        //payments
        $this->app->bind(IPaymentRepository::class, PaymentRepository::class);
        $this->app->bind(PaymentService::class, function ($app) {
            return new PaymentService($app->make(IPaymentRepository::class));
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
