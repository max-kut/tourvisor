<?php

namespace Tourvisor\Integrations\Laravel;

use Illuminate\Support\ServiceProvider;
use Tourvisor\Client;
use Tourvisor\Requests\ActualizeDetailRequest;
use Tourvisor\Requests\ActualizeRequest;
use Tourvisor\Requests\HotelRequest;
use Tourvisor\Requests\HotToursRequest;
use Tourvisor\Requests\ListRequest;
use Tourvisor\Requests\SearchRequest;
use Tourvisor\Requests\SearchResultRequest;
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

        // register requests aliases
        $this->app->alias(SearchRequest::class, 'tourvisor.search');
        $this->app->alias(SearchResultRequest::class, 'tourvisor.result');
        $this->app->alias(ListRequest::class, 'tourvisor.list');
        $this->app->alias(ActualizeRequest::class, 'tourvisor.actualize');
        $this->app->alias(ActualizeDetailRequest::class, 'tourvisor.actualize_detail');
        $this->app->alias(HotelRequest::class, 'tourvisor.hotel');
        $this->app->alias(HotToursRequest::class, 'tourvisor.hot');
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