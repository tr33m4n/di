<?php

declare(strict_types=1);

namespace tr33m4n\Di;

use ReflectionClass;
use ReflectionException;
use tr33m4n\Di\Container\GetParameters;
use tr33m4n\Di\Container\GetPreference;
use tr33m4n\Di\Exception\MissingClassException;

/**
 * Class Container
 *
 * @package tr33m4n\Di
 */
final class Container
{
    /**
     * @var \tr33m4n\Di\Container\GetParameters
     */
    private $getParameters;

    /**
     * @var \tr33m4n\Di\Container\GetPreference
     */
    private $getPreference;

    /**
     * @var object[]
     */
    private $instantiatedClasses = [];

    /**
     * Container constructor.
     *
     * @param \tr33m4n\Di\Container\GetParameters $getParameters
     * @param \tr33m4n\Di\Container\GetPreference $getPreference
     */
    public function __construct(
        GetParameters $getParameters,
        GetPreference $getPreference
    ) {
        $this->getParameters = $getParameters;
        $this->getPreference = $getPreference;
    }

    /**
     * Check if class parameter can be instantiated
     *
     * @param mixed $classParameter Class parameter
     * @return bool
     */
    private function canClassParameterBeInstantiated($classParameter): bool
    {
        return is_string($classParameter)
            && (class_exists($classParameter) || interface_exists($classParameter, false));
    }

    /**
     * Create instantiated class
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param class-string         $className  Class name
     * @param array<string, mixed> $parameters Additional parameters to pass when instantiating the class
     * @return object
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
        return $reflectionClass->newInstanceArgs(array_map(function ($classParameter) {
            return $this->canClassParameterBeInstantiated($classParameter)
                ? $this->get($classParameter)
                : $classParameter;
        }, array_values($classParameters)));
    }

    /**
     * Get class from DI
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param class-string $className Class name to get
     * @return object
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
}
