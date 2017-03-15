<?php

/*
 * This file is part of Casa-Parks/Extract-Routes.
 *
 * (c) Connor S. Parks
 */

namespace CasaParks\ExtractRoutes;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use JsonSerializable;

class Service implements Arrayable, Jsonable, JsonSerializable
{
    /**
     * The available routes.
     *
     * @var array
     */
    protected $routes;

    /**
     * Constructs a new route extracter service.
     *
     * @param \Illuminate\Routing\RouteCollection $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * Constructs a new route extracter service from a route collection.
     *
     * @param \Illuminate\Routing\RouteCollection $routes
     *
     * @return \CasaParks\ExtractRoutes\Service
     */
    public static function from(RouteCollection $routes)
    {
        $mapped = collect($routes)->map(function (Route $route) {
            return [
                'methods' => $route->methods(),
                'secure' => $route->secure(),
                'domain' => $route->domain(),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => $route->middleware(),
                'parameters' => $route->parameterNames(),
            ];
        })->toArray();

        return new static(array_values($mapped));
    }

    /**
     * Filter the routes to a new service.
     *
     * @param callable $callback
     *
     * @return \CasaParks\ExtractRoutes\Service
     */
    public function filter(callable $callback)
    {
        $filtered = collect($this->routes)
            ->filter($callback)
            ->toArray();

        return new static(array_values($filtered));
    }

    /**
     * Filter via a specific $key for only $values.
     *
     * @param string    $key
     * @param ...string $values
     *
     * @return \CasaParks\ExtractRoutes\Service
     */
    public function filterOnly($key, ...$values)
    {
        return $this->filter(function (array $route) use ($key, $values) {
            $comparison = $route[$key];
            if (! is_array($route[$key])) {
                $comparison = [$route[$key]];
            }

            $diff = array_diff($comparison, $values);

            return count($diff) === 0;
        });
    }

    /**
     * Filter via a specific $key for only _except_ $values.
     *
     * @param string    $key
     * @param ...string $values
     *
     * @return \CasaParks\ExtractRoutes\Service
     */
    public function filterExcept($key, ...$values)
    {
        return $this->filter(function (array $route) use ($key, $values) {
            $comparison = $route[$key];
            if (! is_array($route[$key])) {
                $comparison = [$route[$key]];
            }

            $diff = array_diff($comparison, $values);

            return count($diff) === count($comparison);
        });
    }

    /**
     * Filter via a specific $key for a $value match.
     *
     * @param string $key
     * @param string $value
     *
     * @return \CasaParks\ExtractRoutes\Service
     */
    public function filterIs($key, $pattern)
    {
        return $this->filter(function (array $route) use ($key, $pattern) {
            return str_is($pattern, $route[$key]);
        });
    }

    /**
     * Filter via a specific $key for no $value match.
     *
     * @param string $key
     * @param string $value
     *
     * @return \CasaParks\ExtractRoutes\Service
     */
    public function filterIsNot($key, $pattern)
    {
        return $this->filter(function (array $route) use ($key, $pattern) {
            return ! str_is($pattern, $route[$key]);
        });
    }

    /**
     * Key the routes to a new service.
     *
     * @param string $key
     *
     * @return \CasaParks\ExtractRoutes\Service
     */
    public function keyBy($key)
    {
        $keyed = collect($this->routes)
            ->whereNotInStrict($key, [null, ''])
            ->keyBy($key)
            ->toArray();

        return new static($keyed);
    }

    /**
     * Convert the routes to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->routes;
    }

    /**
     * Convert the routes to JSON.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return '{}';
        }

        return $json;
    }

    /**
     * Convert the routes into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
