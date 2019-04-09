<?php

namespace DanielDoyle\HappyDi;

use DanielDoyle\HappyDi\Container\ClassParameterResolver;
use DanielDoyle\HappyDi\Container\PreferenceResolver;
use DanielDoyle\HappyDi\Exception\MissingClassException;
use DanielDoyle\HappyDi\Exception\ReflectionException;

/**
 * Class Container
 *
 * @package DanielDoyle\HappyDi
 */
final class Container
{
    /**
     * @var \DanielDoyle\HappyDi\Container\ClassParameterResolver
     */
    private $classParameterResolver;

    /**
     * @var \DanielDoyle\HappyDi\Container\PreferenceResolver
     */
    private $preferenceResolver;

    /**
     * Container constructor.
     *
     * @param \DanielDoyle\HappyDi\Container\ClassParameterResolver $classParameterResolver
     * @param \DanielDoyle\HappyDi\Container\PreferenceResolver     $preferenceResolver
     */
    public function __construct(
        ClassParameterResolver $classParameterResolver,
        PreferenceResolver $preferenceResolver
    ) {
        $this->classParameterResolver = $classParameterResolver;
        $this->preferenceResolver = $preferenceResolver;
    }

    /**
     * Check if class parameter can be instantiated
     *
     * @param mixed $classParameter Class parameter
     * @return bool
     */
    protected function canClassParameterBeInstantiated($classParameter) : bool
    {
        return is_string($classParameter)
            && (class_exists($classParameter) || interface_exists($classParameter, false));
    }

    /**
     * Create instantiated class
     *
     * @throws ReflectionException
     * @param string $className Class name
     * @return object
     */
    protected function createInstantiatedClass(string $className)
    {
        $className = $this->preferenceResolver->resolve($className);

        // Ensure class is instantiable
        $reflectionClass = new \ReflectionClass($className);
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
     * @throws MissingClassException
     * @param string $className Class name to get
     * @return object
     */
    public function get(string $className)
    {
        // Make sure class exists
        if (!class_exists($className) && !interface_exists($className, false)) {
            throw new MissingClassException(sprintf('%s does not exist!', $className));
        }

        // Instantiate class from DI
        return $this->createInstantiatedClass($className);
    }
}
