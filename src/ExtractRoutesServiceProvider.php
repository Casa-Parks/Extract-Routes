<?php

/*
 * This file is part of Casa-Parks/Extract-Routes.
 *
 * (c) Connor S. Parks
 */

namespace CasaParks\ExtractRoutes;

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
        // ...
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
