<?php

namespace Nrg\Http\Service;

use Nrg\Di\Abstraction\Injector;
use Nrg\Http\Abstraction\RouteProvider;
use Nrg\Http\Exception\NotFoundException;
use Nrg\Http\Value\HttpRequest;
use Nrg\Http\Value\Url;
use Nrg\Utility\Abstraction\Settings;

/**
 * Class HttpRouteProvider.
 *
 * HTTP route provider implementation.
 */
class HttpRouteProvider implements RouteProvider
{
    /**
     * @var Injector
     */
    private $injector;

    /**
     * @var HttpRequest
     */
    private $request;

    /**
     * @var array
     */
    private $routes = [];

    /**
     * HttpRouteProvider constructor.
     *
     * @param Injector $injector
     * @param HttpRequest $request
     * @param Settings $settings
     */
    public function __construct(Injector $injector, HttpRequest $request, Settings $settings)
    {
        $this->injector = $injector;
        $this->request = $request;

        foreach ($settings->getRoutes() as $route => $action) {
            $this->when($route, $action);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function when(string $route, $action): RouteProvider
    {
        $this->routes[$route] = $action;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction()
    {
        $route = $this->request->getUrl()->getPath();
        $method = $this->request->getMethod();
        $action = $this->routes["{$method}:{$route}"] ?? $this->routes[$route] ?? null;

        if (null === $action) {
            throw new NotFoundException();
        }

        return is_object($action) ? $action : $this->injector->createObject($action);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutePaths(): array
    {
        $paths = [];
        foreach ($this->routes as $route => $action) {
            $paths[] = false === strpos($route, ':') ? $route : explode(':', $route)[1];
        }

        return $paths;
    }

    /**
     * {@inheritdoc}
     */
    public function createUrl(string $path, array $params = []): Url
    {
        return $this->request->getUrl()
            ->makeClone()
            ->setPath($path)
            ->setQueryParams($params);
    }
}
