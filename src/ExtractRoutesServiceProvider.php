<?php

/*
 * This file is part of Casa-Parks/Extract-Routes.
 *
 * (c) Connor S. Parks
 */

namespace CasaParks\ExtractRoutes;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\ServiceProvider;

class ExtractRoutesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Service::class, function (Container $container) {
            $registrar = $container->make(Registrar::class);

            if (! method_exists($registrar, 'getRoutes')) {
                return new Service;
            }

            return Service::from($registrar->getRoutes());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Service::class];
    }
}
