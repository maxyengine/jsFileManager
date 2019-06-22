<?php

namespace Nrg\Http\Abstraction;

use Nrg\Http\Exception\NotFoundException;
use Nrg\Http\Value\Url;

use ReflectionException;

/**
 * Interface RouteProvider.
 */
interface RouteProvider
{
    /**
     * Sets the action corresponding to the route.
     *
     * @param string $route
     * @param string $action
     *
     * @return RouteProvider
     */
    public function when(string $route, $action): self;

    /**
     * Returns the action corresponding to the request.
     *
     * @return object
     *
     * @throws ReflectionException
     * @throws NotFoundException
     */
    public function getAction();

    /**
     * @return array
     */
    public function getRoutePaths(): array;

    /**
     * @param string $path
     * @param array $params
     *
     * @return Url
     */
    public function createUrl(string $path, array $params = []): Url;
}
