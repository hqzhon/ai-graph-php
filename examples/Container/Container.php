<?php

namespace App\Examples\Container;

use Psr\Container\ContainerInterface;
use App\Examples\Exception\ContainerException;
use App\Examples\Exception\NotFoundException;

class Container implements ContainerInterface
{
    private $definitions = [];
    private $instances = [];

    public function __construct()
    {
        // Register the container itself so it can be injected.
        $this->instance(ContainerInterface::class, $this);
        $this->instance(self::class, $this);
    }

    /**
     *
     * Define a shared (singleton) service.
     *
     * @param string $abstract
     * @param mixed $concrete
     */
    public function singleton(string $abstract, $concrete = null): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        $this->definitions[$abstract] = ['concrete' => $concrete, 'shared' => true];
    }

    /**
     *
     * Define a non-shared (factory) service.
     *
     * @param string $abstract
     * @param mixed $concrete
     */
    public function bind(string $abstract, $concrete = null): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        $this->definitions[$abstract] = ['concrete' => $concrete, 'shared' => false];
    }

    /**
     *
     * Register an existing instance as a shared service.
     *
     * @param string $abstract
     * @param mixed $instance
     */
    public function instance(string $abstract, $instance): void
    {
        $this->instances[$abstract] = $instance;
    }

    /**
     *
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     * @return mixed No entry was found for **this** identifier.
     * @throws NotFoundException  No entry was found for **this** identifier.
     * @throws ContainerException Error while retrieving the entry.
     */
    public function get(string $id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        $definition = $this->definitions[$id] ?? ['concrete' => $id, 'shared' => true];

        $concrete = $definition['concrete'];

        if ($concrete instanceof \Closure) {
            $object = $concrete($this);
        } elseif (is_string($concrete) && class_exists($concrete)) {
            $object = $this->resolve($concrete);
        } else {
            $object = $concrete;
        }

        if ($definition['shared']) {
            $this->instances[$id] = $object;
        }

        return $object;
    }

    /**
     *
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param string $id Identifier of the entry to look for.
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->definitions[$id]) || isset($this->instances[$id]) || class_exists($id);
    }

    /**
     *
     * Resolve a class and its dependencies from the container.
     *
     * @param string $class
     * @return object
     * @throws ContainerException
     */
    private function resolve(string $class): object
    {
        try {
            $reflector = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new ContainerException("Class {$class} not found.", 0, $e);
        }

        if (!$reflector->isInstantiable()) {
            throw new ContainerException("Class {$class} is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $class;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getType();

            if ($dependency === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                    continue;
                }
                throw new ContainerException("Cannot resolve constructor parameter '{$parameter->getName()}' in class {$class} because it has no type hint.");
            }

            if ($dependency instanceof \ReflectionNamedType && !$dependency->isBuiltin()) {
                $dependencies[] = $this->get($dependency->getName());
            } else {
                 if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                    continue;
                }
                throw new ContainerException("Cannot resolve constructor parameter '{$parameter->getName()}' in class {$class} because it is a built-in type.");
            }
        }

        return $reflector->newInstanceArgs($dependencies);
    }
}