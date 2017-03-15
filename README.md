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

_**TODO**_
