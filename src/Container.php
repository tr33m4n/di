<?php

declare(strict_types=1);

namespace tr33m4n\Di;

use Closure;
use ReflectionClass;
use ReflectionException;
use tr33m4n\Di\Container\GetParameters;
use tr33m4n\Di\Container\GetPreference;
use tr33m4n\Di\Exception\MissingClassException;

final class Container
{
    /**
     * @var object[]
     */
    private array $instantiatedClasses = [];

    /**
     * Container constructor.
     */
    public function __construct(
        private readonly GetParameters $getParameters,
        private readonly GetPreference $getPreference
    ) {
    }

    /**
     * Create instantiated class
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\ConfigException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @param class-string         $className  Class name
     * @param array<string, mixed> $parameters Additional parameters to pass when instantiating the class
     */
    public function create(string $className, array $parameters = []): object
    {
        // Resolve preference and ensure class exists
        $className = $this->getPreference->execute($className);
        if (!class_exists($className) && !interface_exists($className, false)) {
            throw new MissingClassException(sprintf('%s does not exist!', $className));
        }

        // Ensure class is instantiable
        $reflectionClass = new ReflectionClass($className);
        if (!$reflectionClass->isInstantiable()) {
            throw new ReflectionException(sprintf('%s is not instantiable!', $className));
        }

        // Get class params using DI config
        $classParameters = $this->getParameters->execute($reflectionClass, $parameters);
        if (empty($classParameters)) {
            return new $className();
        }

        // Return new instance with resolved arguments
        return $reflectionClass->newInstanceArgs(
            array_map($this->newInstanceIteratee(), array_values($classParameters))
        );
    }

    /**
     * Get class from DI
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\ConfigException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @param class-string $className Class name to get
     */
    public function get(string $className): object
    {
        $className = ltrim($className, '\\');

        // Return shared class if already instantiated and parameters are the same
        if (array_key_exists($className, $this->instantiatedClasses)) {
            return $this->instantiatedClasses[$className];
        }

        /** @var class-string $className */
        return $this->instantiatedClasses[$className] = $this->create($className);
    }

    /**
     * New instance iteratee
     */
    private function newInstanceIteratee(): Closure
    {
        return function ($classParameter) {
            if ($this->canClassParameterBeInstantiated($classParameter)) {
                /** @var class-string $classParameter */
                return $this->get($classParameter);
            }
            return $classParameter;
        };
    }

    /**
     * Check if class parameter can be instantiated
     */
    private function canClassParameterBeInstantiated(mixed $classParameter): bool
    {
        return is_string($classParameter)
            && (class_exists($classParameter) || interface_exists($classParameter, false));
    }
}
