<?php

namespace tr33m4n\HappyDi;

use tr33m4n\HappyDi\Container\ClassParameterResolver;
use tr33m4n\HappyDi\Container\PreferenceResolver;
use tr33m4n\HappyDi\Container\SharedResolver;
use tr33m4n\HappyDi\Exception\MissingClassException;
use ReflectionException;
use ReflectionClass;

/**
 * Class Container
 *
 * @package tr33m4n\HappyDi
 */
final class Container
{
    /**
     * @var \tr33m4n\HappyDi\Container\ClassParameterResolver
     */
    private $classParameterResolver;

    /**
     * @var \tr33m4n\HappyDi\Container\PreferenceResolver
     */
    private $preferenceResolver;

    /**
     * @var \tr33m4n\HappyDi\Container\SharedResolver
     */
    private $sharedResolver;

    /**
     * @var object[]
     */
    private $sharedInstantiatedClasses = [];

    /**
     * Container constructor.
     *
     * @param \tr33m4n\HappyDi\Container\ClassParameterResolver $classParameterResolver
     * @param \tr33m4n\HappyDi\Container\PreferenceResolver     $preferenceResolver
     * @param \tr33m4n\HappyDi\Container\SharedResolver         $sharedResolver
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
    private function canClassParameterBeInstantiated($classParameter) : bool
    {
        return is_string($classParameter)
            && (class_exists($classParameter) || interface_exists($classParameter, false));
    }

    /**
     * Create instantiated class
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\HappyUtilities\Exception\MissingConfigException
     * @throws \tr33m4n\HappyUtilities\Exception\RegistryException
     * @param string $className Class name
     * @return object
     */
    private function createInstantiatedClass(string $className)
    {
        $className = $this->preferenceResolver->resolve($className);

        // Ensure class is instantiable
        $reflectionClass = new ReflectionClass($className);
        if (!$reflectionClass->isInstantiable()) {
            throw new ReflectionException(sprintf('%s is not instantiable!', $className));
        }

        // Resolve class params using DI config
        $classParameters = $this->classParameterResolver->resolve($reflectionClass);
        if (empty($classParameters)) {
            return new $className;
        }

        // Return new instance with resolved arguments
        return $reflectionClass->newInstanceArgs(array_map(function ($classParameter) {
            return $this->canClassParameterBeInstantiated($classParameter)
                ? $this->get($classParameter)
                : $classParameter;
        }, $classParameters));
    }

    /**
     * Get class from DI
     *
     * @throws \ReflectionException
     * @throws MissingClassException
     * @throws \tr33m4n\HappyUtilities\Exception\MissingConfigException
     * @throws \tr33m4n\HappyUtilities\Exception\RegistryException
     * @param string $className Class name to get
     * @return object
     */
    public function get(string $className)
    {
        // Make sure class exists
        if (!class_exists($className) && !interface_exists($className, false)) {
            throw new MissingClassException(sprintf('%s does not exist!', $className));
        }

        // Return shared class if already instantiated
        if (array_key_exists($className, $this->sharedInstantiatedClasses)) {
            return $this->sharedInstantiatedClasses[$className];
        }

        // Should the class be set as shared
        $isSetShared = $this->sharedResolver->resolve($className);
        // Instantiate class from DI
        $instantiatedClass = $this->createInstantiatedClass($className);

        if ($isSetShared) {
            return $this->sharedInstantiatedClasses[$className] = $instantiatedClass;
        }

        return $instantiatedClass;
    }
}
