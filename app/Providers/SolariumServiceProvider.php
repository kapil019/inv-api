<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Solarium\Client;
use Solarium\Client as SolrClient;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher;


class SolariumServiceProvider extends ServiceProvider
{
    protected $defer = true;

    protected $options = [];

    /**
     * Register any application services.
     *
     * @return  void
     */
    public function register()
    {
        $this->app->bind(Client::class, function ($app) {
            $adapter = new Curl();
            $eventDispatcher = new EventDispatcher();
            return new SolrClient($adapter, $eventDispatcher, $app['config']['solarium']);
        });
    }

    public function provides()
    {
        return [Client::class];
    }
}