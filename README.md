<p align="center">
<a href="https://travis-ci.org/Casa-Parks/Extract-Routes"><img src="https://travis-ci.org/Casa-Parks/Extract-Routes.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/Casa-Parks/Extract-Routes"><img src="https://poser.pugx.org/Casa-Parks/Extract-Routes/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/Casa-Parks/Extract-Routes"><img src="https://poser.pugx.org/Casa-Parks/Extract-Routes/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/Casa-Parks/Extract-Routes"><img src="https://poser.pugx.org/Casa-Parks/Extract-Routes/license.svg" alt="License"></a>
</p>

## Introduction

Extract Routes is a simple provision of route listing, designed for providing use to the front end (IE: in JavaScript).

## License

Extract Routes is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Installation

To get started with Extract Routes, use Composer to add the package to your project's dependencies:

    composer require casa-parks/extract-routes

### Configuration

After installing, register the `CasaParks\ExtractRoutes\ExtractRoutesServiceProvider` in your `config/app.php` configuration file:

```php
'providers' => [
    // Other service providers...

    CasaParks\ExtractRoutes\ExtractRoutesServiceProvider::class,
],
```

### Basic Usage

Create a simple view composer, like so:

```php
<?php

namespace App\Composers;

use CasaParks\ExtractRoutes\Service as RoutesExtractor;
use Illuminate\Contracts\View\View;

class RoutesComposer
{
    /**
     * The routes extractor.
     *
     * @var \CasaParks\ExtractRoutes\Service
     */
    protected $extractor;

    /**
     * Whether the data is cached or not.
     *
     * @var bool
     */
    protected $cached;

    /**
     * The view data.
     *
     * @var array
     */
    protected $data;

    /**
     * Creates a new routes composer.
     *
     * @param \CasaParks\ExtractRoutes\Service $extractor
     */
    public function __construct(RoutesExtractor $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * Compose the view.
     *
     * @param \Illuminate\Contracts\View\View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        if (! $this->cached) {
            $this->cache();
        }

        $view->with($this->data);
    }

    /**
     * Cache the data.
     *
     * @return void
     */
    protected function cache()
    {
        $this->cached = true;

        // We don't want to include any admin / api routes.
        $routes = $this->extractor->filterOnly('middleware', 'guest', 'auth');

        $this->data = compact('routes');
    }
}
```

Add this view composer, into your app (or composer) service provider's `boot` method:

```php
/**
 * Register any composers for your application.
 *
 * @return void
 */
public function boot()
{
    // ...

    // assuming `layout` is your common layout template.
    $this->app['view']->composer('layout', 'App\Composers\RoutesComposer');

    // ...
}
```

In your common `layout` template file:

```blade
<!-- ... -->
<head>
    <!-- ... -->

    <script>window.routes = {!! $routes->toJson() !!}</script>

    <!-- ... -->
</head>
<!-- ... -->
```

Then utilise as required in your JavaScript.
