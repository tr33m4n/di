<?php

declare(strict_types=1);

namespace tr33m4n\Di;

use ReflectionClass;
use ReflectionException;
use tr33m4n\Di\Container\ClassParameterResolver;
use tr33m4n\Di\Container\PreferenceResolver;
use tr33m4n\Di\Container\SharedResolver;
use tr33m4n\Di\Exception\MissingClassException;

/**
 * Class Container
 *
 * @package tr33m4n\Di
 */
final class Container
{
    /**
     * @var \tr33m4n\Di\Container\ClassParameterResolver
     */
    private $classParameterResolver;

    /**
     * @var \tr33m4n\Di\Container\PreferenceResolver
     */
    private $preferenceResolver;

    /**
     * @var \tr33m4n\Di\Container\SharedResolver
     */
    private $sharedResolver;

    /**
     * @var object[]
     */
    private $sharedInstantiatedClasses = [];

    /**
     * Container constructor.
     *
     * @param \tr33m4n\Di\Container\ClassParameterResolver $classParameterResolver
     * @param \tr33m4n\Di\Container\PreferenceResolver     $preferenceResolver
     * @param \tr33m4n\Di\Container\SharedResolver         $sharedResolver
     */
    public function __construct(
        ClassParameterResolver $classParameterResolver,
        PreferenceResolver $preferenceResolver,
        SharedResolver $sharedResolver
    ) {
        $this->classParameterResolver = $classParameterResolver;
        $this->preferenceResolver = $preferenceResolver;
        $this->sharedResolver = $sharedResolver;
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
     * @throws \tr33m4n\Utilities\Exception\RegistryException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @param class-string         $className  Class name
     * @param array<string, mixed> $parameters Additional parameters to pass when instantiating the class
     * @return object
     */
    public function create(string $className, array $parameters): object
    {
        // Resolve class name and ensure class exists
        $className = $this->preferenceResolver->resolve($className);
        if (!class_exists($className) && !interface_exists($className, false)) {
            throw new MissingClassException(sprintf('%s does not exist!', $className));
        }

        // Ensure class is instantiable
        $reflectionClass = new ReflectionClass($className);
        if (!$reflectionClass->isInstantiable()) {
            throw new ReflectionException(sprintf('%s is not instantiable!', $className));
        }

        // Resolve class params using DI config
        $classParameters = $this->classParameterResolver->resolve($reflectionClass, $parameters);
        if (empty($classParameters)) {
            return new $className();
        }

        // Return new instance with resolved arguments
        return $reflectionClass->newInstanceArgs(array_map(function ($classParameter) {
            return $this->canClassParameterBeInstantiated($classParameter)
                ? $this->get($classParameter)
                : $classParameter;
        }, $classParameters));
    }

    /**
     * Get class from DI. Will be cached
     *
     * @throws \ReflectionException
     * @throws MissingClassException
     * @throws \tr33m4n\Utilities\Exception\RegistryException
     * @param class-string         $className  Class name to get
     * @param array<string, mixed> $parameters Additional parameters to pass when instantiating the class
     * @return object
     */
    public function get(string $className, array $parameters = []): object
    {
        // Return shared class if already instantiated
        if (array_key_exists($className, $this->sharedInstantiatedClasses)) {
            return $this->sharedInstantiatedClasses[$className];
        }

        // Instantiate class from DI
        $instantiatedClass = $this->create($className, $parameters);
        // Should the class be set as shared
        if ($this->sharedResolver->resolve($className)) {
            return $this->sharedInstantiatedClasses[$className] = $this->create($className, $parameters);
        }

        return $instantiatedClass;
    }
}
