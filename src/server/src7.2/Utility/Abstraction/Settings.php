<?php

namespace Nrg\Utility\Abstraction;

/**
 * Interface Settings.
 */
interface Settings
{
    public const KEY_SERVICES = 'services';
    public const KEY_EVENTS = 'events';
    public const KEY_ROUTES = 'routes';

    /**
     * @return array
     */
    public function getServices(): array;

    /**
     * @return array
     */
    public function getEvents(): array;

    /**
     * @return array
     */
    public function getRoutes(): array;
}
