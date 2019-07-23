<?php

namespace JinseokOh\BootPay;

use Illuminate\Support\ServiceProvider;

class BootPayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BootPayHandler::class, function ($app) {
            return new BootPayHandler(new BootPayClient());
        });

        $this->app->alias(BootPayHandler::class, 'BootPay');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/bootpay.php' => config_path('bootpay.php'),
        ]);
    }
}
