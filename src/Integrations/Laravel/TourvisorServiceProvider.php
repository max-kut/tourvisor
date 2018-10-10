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
        $this->app->singleton(Tourvisor::class, function ($app) {
            return new Tourvisor(new Client(env('TOURVISOR_LOGIN'), env('TOURVISOR_PASSWORD')));
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