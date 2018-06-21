<?php

namespace Shuke\PayServe\Providers;

use Illuminate\Support\ServiceProvider;
use Shuke\PayServe\Contracts\PayServe;
use Shuke\PayServe\Serve\ComeTruePay;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PayServe::class, function ($app) {
            return new ComeTruePay($app['config']);
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [PayServe::class];
    }
}
