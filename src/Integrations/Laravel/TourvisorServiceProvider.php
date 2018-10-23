<?php

namespace Tourvisor\Integrations\Laravel;

use Illuminate\Support\ServiceProvider;
use Tourvisor\Client;
use Tourvisor\Tourvisor;

class TourvisorServiceProvider extends ServiceProvider
{
    /** @var bool */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/tourvisor.php', 'tourvisor'
        );

        $this->app->singleton(Tourvisor::class, function ($app) {
            return new Tourvisor(new Client(config('tourvisor.login'), config('tourvisor.password')));
        });

        $this->app->alias(Tourvisor::class, 'tourvisor');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Tourvisor::class];
    }
}