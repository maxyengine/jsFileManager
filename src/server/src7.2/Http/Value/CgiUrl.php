<?php

namespace Nrg\Http\Value;

/**
 * Class CgiUrl.
 *
 * Url composed of CGI environment data.
 */
class CgiUrl extends Url
{
    public function __construct()
    {
        parent::__construct(
            $this->createCurrentUrl(),
            $this->createBasePath()
        );
    }

    /**
     * @return string
     */
    private function createCurrentUrl(): string
    {
        return sprintf(
            "%s%s%s",
            $this->createProtocol(),
            $_SERVER['HTTP_HOST'],
            $_SERVER['REQUEST_URI']
        );
    }

    /**
     * @return string
     */
    private function createBasePath(): string
    {
        return str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));
    }

    /**
     * Returns Url protocol for current environment.
     *
     * @return string
     */
    private function createProtocol(): string
    {
        return (!empty($_SERVER['HTTPS']) && 'off' !== $_SERVER['HTTPS'] || 443 === $_SERVER['SERVER_PORT']) ?
            'https://' : 'http://';
    }
}
