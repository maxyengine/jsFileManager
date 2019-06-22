<?php

namespace Nrg\Uploader\Middleware;

use Nrg\Di\Abstraction\Injector;
use Nrg\Uploader\Service\AppConfig;
use Nrg\Uploader\Service\DemoConfig;
use Nrg\Utility\Abstraction\Config;

/**
 * Class UseIfDemo
 */
class UseIfDemo
{
    /**
     * @param Injector $injector
     * @param Config | AppConfig $config
     */
    public function __construct(Injector $injector, Config $config)
    {
        if ($config->get('isDemo', false)) {
            $injector->setService(
                Config::class,
                [
                    DemoConfig::class,
                    'path' => $config->getPath(),
                    'publicKeys' => array_keys($config->getPublic()),
                ]
            );
        }
    }
}
