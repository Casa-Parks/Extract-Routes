<?php

/*
 * This file is part of Casa-Parks/Extract-Routes.
 *
 * (c) Connor S. Parks
 */

namespace Tests;

use CasaParks\ExtractRoutes\Service;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Mockery;
use PHPUnit_Framework_TestCase;

class ServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tear down the test case.
     *
     * @return void
     */
    public function tearDown()
    {
        Mockery::close();
    }

    public function testServiceIsFrontendAccessable()
    {
        $service = new Service([
            ['a' => 'baz', 'b' => ['foo']],
            ['a' => 'baz', 'b' => ['bee']],
            ['a' => 'bee', 'b' => ['foo']],
            ['a' => 'hello', 'b' => ['world']],
        ]);

        $this->assertEquals([
            ['a' => 'baz', 'b' => ['foo']],
            ['a' => 'baz', 'b' => ['bee']],
            ['a' => 'bee', 'b' => ['foo']],
            ['a' => 'hello', 'b' => ['world']],
        ], $service->toArray());

        $this->assertEquals([
            ['a' => 'baz', 'b' => ['foo']],
            ['a' => 'baz', 'b' => ['bee']],
            ['a' => 'bee', 'b' => ['foo']],
            ['a' => 'hello', 'b' => ['world']],
        ], $service->jsonSerialize());

        $this->assertEquals('[{"a":"baz","b":["foo"]},{"a":"baz","b":["bee"]},{"a":"bee","b":["foo"]},{"a":"hello","b":["world"]}]', $service->toJson());
        $this->assertEquals('[
    {
        "a": "baz",
        "b": [
            "foo"
        ]
    },
    {
        "a": "baz",
        "b": [
            "bee"
        ]
    },
    {
        "a": "bee",
        "b": [
            "foo"
        ]
    },
    {
        "a": "hello",
        "b": [
            "world"
        ]
    }
]', $service->toJson(JSON_PRETTY_PRINT));
    }

    public function testEmptyRoutes()
    {
        $service = new Service();

        $this->assertEquals([], $service->toArray());
    }

    public function testFiltersOnly()
    {
        $service = new Service([
            ['a' => 'baz', 'b' => ['foo']],
            ['a' => 'baz', 'b' => ['bee']],
            ['a' => 'bee', 'b' => ['foo']],
            ['a' => 'hello', 'b' => ['world']],
        ]);

        $this->assertEquals([
            ['a' => 'baz', 'b' => ['foo']],
            ['a' => 'baz', 'b' => ['bee']],
        ], $service->filterOnly('a', 'baz')->toArray());

        $this->assertEquals([
            ['a' => 'baz', 'b' => ['foo']],
            ['a' => 'baz', 'b' => ['bee']],
            ['a' => 'bee', 'b' => ['foo']],
        ], $service->filterOnly('a', 'baz', 'bee')->toArray());

        $this->assertEquals([
            ['a' => 'hello', 'b' => ['world']],
        ], $service->filterOnly('b', 'world')->toArray());

        $this->assertEquals([
            ['a' => 'baz', 'b' => ['foo']],
        ], $service->filterOnly('a', 'baz')->filterOnly('b', 'foo')->toArray());
    }

    public function testFiltersExcept()
    {
        $service = new Service([
            ['a' => 'baz', 'b' => ['foo']],
            ['a' => 'baz', 'b' => ['bee']],
            ['a' => 'bee', 'b' => ['foo']],
            ['a' => 'hello', 'b' => ['world']],
        ]);

        $this->assertEquals([
            ['a' => 'bee', 'b' => ['foo']],
            ['a' => 'hello', 'b' => ['world']],
        ], $service->filterExcept('a', 'baz')->toArray());

        $this->assertEquals([
            ['a' => 'hello', 'b' => ['world']],
        ], $service->filterExcept('a', 'baz', 'bee')->toArray());

        $this->assertEquals([
            ['a' => 'baz', 'b' => ['foo']],
            ['a' => 'baz', 'b' => ['bee']],
            ['a' => 'bee', 'b' => ['foo']],
        ], $service->filterExcept('b', 'world')->toArray());

        $this->assertEquals([
            ['a' => 'hello', 'b' => ['world']],
        ], $service->filterExcept('a', 'baz')->filterExcept('b', 'foo')->toArray());
    }

    public function testFiltersIs()
    {
        $service = new Service([
            ['a' => 'baz', 'b' => 'foo'],
            ['a' => 'baz', 'b' => 'bee'],
            ['a' => 'bee', 'b' => 'foo'],
            ['a' => 'hello', 'b' => 'world'],
        ]);

        $this->assertEquals([
            ['a' => 'baz', 'b' => 'foo'],
            ['a' => 'baz', 'b' => 'bee'],
            ['a' => 'bee', 'b' => 'foo'],
        ], $service->filterIs('a', 'b*')->toArray());

        $this->assertEquals([
            ['a' => 'baz', 'b' => 'foo'],
            ['a' => 'bee', 'b' => 'foo'],
            ['a' => 'hello', 'b' => 'world'],
        ], $service->filterIs('b', '*o*')->toArray());

        $this->assertEquals([
            ['a' => 'baz', 'b' => 'bee'],
        ], $service->filterIs('a', 'b*')->filterIs('b', '*ee')->toArray());
    }

    public function testFiltersIsNot()
    {
        $service = new Service([
            ['a' => 'baz', 'b' => 'foo'],
            ['a' => 'baz', 'b' => 'bee'],
            ['a' => 'bee', 'b' => 'foo'],
            ['a' => 'hello', 'b' => 'world'],
        ]);

        $this->assertEquals([
            ['a' => 'hello', 'b' => 'world'],
        ], $service->filterIsNot('a', 'b*')->toArray());

        $this->assertEquals([
            ['a' => 'baz', 'b' => 'bee'],
        ], $service->filterIsNot('b', '*o*')->toArray());

        $this->assertEquals([
            ['a' => 'hello', 'b' => 'world'],
        ], $service->filterIsNot('a', 'b*')->filterIsNot('b', '*ee')->toArray());
    }

    public function testKeyBy()
    {
        $service = new Service([
            ['k' => 'a', 'a' => 'baz', 'b' => ['foo']],
            ['k' => 'b', 'a' => 'baz', 'b' => ['bee']],
            ['k' => 'c', 'a' => 'bee', 'b' => ['foo']],
            ['k' => 'd', 'a' => 'hello', 'b' => ['world']],
        ]);

        $this->assertEquals([
            'baz' => ['k' => 'b', 'a' => 'baz', 'b' => ['bee']],
            'bee' => ['k' => 'c', 'a' => 'bee', 'b' => ['foo']],
            'hello' => ['k' => 'd', 'a' => 'hello', 'b' => ['world']],
        ], $service->keyBy('a')->toArray());
    }

    public function testRouteCollectionCreation()
    {
        $collection = new RouteCollection();

        $collection->add((new Route(['HEAD', 'GET'], '/', ['HomeController', 'show']))->name('home'));
        $collection->add((new Route(['HEAD', 'GET'], '/settings', ['SettingsController', 'show']))->prefix('me')->middleware('auth'));
        $collection->add((new Route(['POST'], '/settings', ['SettingsController', 'store']))->prefix('me')->middleware('auth'));
        $collection->add((new Route(['HEAD', 'GET'], '/@{user}', ['ProfileController', 'show']))->name('profile.show')->middleware('auth', 'admin'));

        $service = Service::from($collection);

        $this->assertEquals([
            [
                'methods' => ['HEAD', 'GET'],
                'secure' => false,
                'domain' => null,
                'uri' => '/',
                'name' => 'home',
                'action' => 'Closure',
                'middleware' => [],
                'parameters' => [],
            ],
            [
                'methods' => ['HEAD', 'GET'],
                'secure' => false,
                'domain' => null,
                'uri' => 'me/settings',
                'name' => null,
                'action' => 'Closure',
                'middleware' => ['auth'],
                'parameters' => [],
            ],
            [
                'methods' => ['POST'],
                'secure' => false,
                'domain' => null,
                'uri' => 'me/settings',
                'name' => null,
                'action' => 'Closure',
                'middleware' => ['auth'],
                'parameters' => [],
            ],
            [
                'methods' => ['HEAD', 'GET'],
                'secure' => false,
                'domain' => null,
                'uri' => '/@{user}',
                'name' => 'profile.show',
                'action' => 'Closure',
                'middleware' => ['auth', 'admin'],
                'parameters' => ['user'],
            ],
        ], $service->toArray());
    }
}
