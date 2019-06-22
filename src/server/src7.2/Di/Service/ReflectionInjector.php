<?php

namespace Nrg\Di\Service;

use Nrg\Di\Abstraction\Injector;
use Nrg\Utility\Abstraction\Settings;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Class ReflectionInjector.
 *
 * Dependency injection pattern implementation.
 */
class ReflectionInjector implements Injector
{
    /**
     * List of services definitions.
     *
     * @var array
     */
    private $services = [];

    /**
     * @param array $services
     */
    public function __construct(array $services = [])
    {
        $this
            ->loadServices($services)
            ->setService(Injector::class, $this);
    }

    public static function createBySettings(Settings $settings): Injector
    {
        return (new self())
            ->setService(Settings::class, $settings)
            ->loadServices($settings->getServices());
    }

    /**
     * {@inheritdoc}
     */
    public function createObject(string $class, array $args = [])
    {
        $class = new ReflectionClass($class);

        return $class->hasMethod('__construct') ?
            $class->newInstanceArgs($this->resolveArguments($class->getMethod('__construct')->getParameters(), $args)) :
            $class->newInstance();
    }

    /**
     * {@inheritdoc}
     */
    public function createObjectByDefinition($definition)
    {
        if (is_array($definition)) {
            return $this->createObject(array_shift($definition), $definition);
        }
        if (is_callable($definition)) {
            $callable = new ReflectionFunction($definition);

            return $callable->invokeArgs($this->resolveArguments($callable->getParameters()));
        }

        return $this->createObject($definition);
    }

    /**
     * {@inheritdoc}
     */
    public function invokeMethod($object, string $name, array $args = [])
    {
        $method = new ReflectionMethod($object, $name);

        return $method->invokeArgs($object, $this->resolveArguments($method->getParameters(), $args));
    }

    /**
     * {@inheritdoc}
     */
    public function invokeFunction(callable $function, array $args = [])
    {
        $function = new ReflectionFunction($function);

        return $function->invokeArgs($this->resolveArguments($function->getParameters(), $args));
    }

    /**
     * {@inheritdoc}
     */
    public function getService(string $interface)
    {
        if ($this->hasDefinition($interface)) {
            return $this->createService($interface);
        }

        foreach ($this->services as $serviceInterface => $definition) {
            $service = new ReflectionClass($serviceInterface);
            if ($service->isSubclassOf($interface)) {
                $this->setService($interface, $this->createService($serviceInterface));

                return $this->services[$interface];
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setService(string $interface, $definition): Injector
    {
        $this->services[$interface] = $definition;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function loadServices(array $definitions): Injector
    {
        foreach ($definitions as $interface => $definition) {
            $this->setService($interface, $definition);
        }

        return $this;
    }

    /**
     * Creates a service object.
     *
     * @param string $interface
     *
     * @return object
     * @throws ReflectionException
     */
    private function createService(string $interface)
    {
        $definition = $this->services[$interface];
        if (!is_object($definition) || is_callable($definition)) {
            $this->services[$interface] = $this->createObjectByDefinition($definition);
        }

        return $this->services[$interface];
    }

    /**
     * @param string $interface
     *
     * @return bool
     */
    private function hasDefinition(string $interface): bool
    {
        return isset($this->services[$interface]);
    }

    /**
     * Resolves arguments using services.
     *
     * @param ReflectionParameter[] $parameterReflections
     * @param array                 $passed
     *
     * @return array
     * @throws ReflectionException
     */
    private function resolveArguments(array $parameterReflections, array $passed = []): array
    {
        $arguments = [];
        foreach ($parameterReflections as $param) {
            if (isset($passed[$param->name])) {
                $arguments[] = $passed[$param->name];
            } elseif (null !== $param->getClass()) {
                $class = $param->getClass()->name;
                $service = $this->getService($class);
                if (null !== $service) {
                    $arguments[] = $service;
                } elseif ($param->isDefaultValueAvailable()) {
                    $arguments[] = $param->getDefaultValue();
                } else {
                    $arguments[] = $this->createObject($class);
                }
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            }
        }

        return $arguments;
    }
}
