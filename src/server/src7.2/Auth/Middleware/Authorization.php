<?php

namespace Nrg\Auth\Middleware;

use Nrg\Auth\Abstraction\AuthControl;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Exception\UnauthorizedException;
use Nrg\Http\Value\HttpRequest;
use Nrg\Utility\Abstraction\Config;

/**
 * Class Authorization
 */
class Authorization
{
    public const CONFIG_KEY = 'authorization';
    public const HEADER_NAME = 'Authorization';
    public const SCHEME_NAME = 'Bearer';

    /**
     * @var bool
     */
    private $authorization;

    /**
     * @var AuthControl
     */
    private $authControl;

    /**
     * @var array
     */
    private $freeAccessRoutes;

    /**
     * @var string|null
     */
    private $refreshAccessRoute;

    /**
     * @param Config $config
     * @param AuthControl $authControl
     * @param array $freeAccessRoutes
     * @param string|null $refreshAccessRoute
     */
    public function __construct(
        Config $config,
        AuthControl $authControl,
        array $freeAccessRoutes = [],
        string $refreshAccessRoute = null
    ) {
        $this->authControl = $authControl;
        $this->freeAccessRoutes = $freeAccessRoutes;
        $this->refreshAccessRoute = $refreshAccessRoute;
        $this->authorization = $config->get(self::CONFIG_KEY, true);
    }

    /**
     * @param HttpExchangeEvent $event
     *
     * @throws UnauthorizedException
     */
    public function onNext(HttpExchangeEvent $event)
    {
        if (!$this->authorization) {
            return;
        }

        $request = $event->getRequest();

        if ($this->hasFreeAccess($request)) {
            return;
        }

        $header = $request->getQueryParam(self::HEADER_NAME) ?? $request->getHeaderLine(self::HEADER_NAME);

        if (empty($header)) {
            throw new UnauthorizedException();
        }

        if (!$this->verifyScheme($header)) {
            throw new UnauthorizedException('Unsupported authorization scheme');
        }

        $token = $this->extractToken($header);

        if ($this->forRefreshAccessToken($request) ?
            !$this->authControl->verifyRefreshToken($token) :
            !$this->authControl->verifyAccessToken($token)
        ) {
            throw new UnauthorizedException('Invalid token was provided');
        }

        $this->authControl->setToken($token);
    }

    private function forRefreshAccessToken(HttpRequest $request): bool
    {
        return null !== $this->refreshAccessRoute && $request->getUrl()->getPath() === $this->refreshAccessRoute;
    }

    private function hasFreeAccess(HttpRequest $request): bool
    {
        return in_array($request->getUrl()->getPath(), $this->freeAccessRoutes);
    }

    private function verifyScheme(string $header)
    {
        return substr($header, 0, strlen(self::SCHEME_NAME) + 1) === self::SCHEME_NAME.' ';
    }

    private function extractToken(string $header)
    {
        return substr($header, strlen(self::SCHEME_NAME) + 1);
    }
}
