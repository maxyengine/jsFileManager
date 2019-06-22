<?php

namespace Nrg\Di\Abstraction;

use ReflectionException;

/**
 * Interface Injector.
 */
interface Injector
{
    /**
     * Creates an object using the list of services definitions.
     *
     * @param string $class
     * @param array  $args
     *
     * @throws ReflectionException
     * @return mixed
     */
    public function createObject(string $class, array $args = []);

    /**
     * @param $definition
     *
     * @throws ReflectionException
     *
     * @return mixed|object
     */
    public function createObjectByDefinition($definition);

    /**
     * Invokes an object method with arguments.
     *
     * @param object $object
     * @param string $name
     * @param array  $args
     *
     * @throws ReflectionException
     * @return mixed method result
     */
    public function invokeMethod($object, string $name, array $args = []);

    /**
     * Invokes a function with arguments.
     *
     * @param callable $function
     * @param array    $args
     *
     * @throws ReflectionException
     * @return mixed function result
     */
    public function invokeFunction(callable $function, array $args = []);

    /**
     * Sets a service definition.
     *
     * @param string                       $interface
     * @param array|callable|object|string $definition
     *
     * @return Injector
     */
    public function setService(string $interface, $definition): Injector;

    /**
     * Returns a service object or null if service was not found.
     *
     * @param string $interface
     *
     * @throws ReflectionException
     * @return null|object
     */
    public function getService(string $interface);

    /**
     * Loads services definitions.
     *
     * @param array $definitions
     *
     * @return Injector
     */
    public function loadServices(array $definitions): Injector;
}
