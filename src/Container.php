<?php

namespace DanielDoyle\HappyDi;

use DanielDoyle\HappyDi\Container\ClassParameterResolver;
use DanielDoyle\HappyDi\Exception\MissingClassException;
use DanielDoyle\HappyDi\Exception\ReflectionException;
use DanielDoyle\HappyUtilities\Helpers\ConfigProvider;

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
     * Container constructor.
     *
     * @param \DanielDoyle\HappyDi\Container\ClassParameterResolver $classParameterResolver
     */
    public function __construct(
        ClassParameterResolver $classParameterResolver = null
    ) {
        $this->classParameterResolver = !$classParameterResolver
            ? $this->getClassParameterResolver()
            : $classParameterResolver;
    }

    /**
     * Get class parameter resolver if not present in the constructor
     *
     * @return \DanielDoyle\HappyDi\Container\ClassParameterResolver
     */
    protected function getClassParameterResolver()
    {
        return new ClassParameterResolver(new ConfigProvider('di', [__DIR__ . '/../config']));
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
            return is_string($classParameter) && class_exists($classParameter)
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
        if (!class_exists($className)) {
            throw new MissingClassException(sprintf('%s does not exist!', $className));
        }

        // Instantiate class from DI
        return $this->createInstantiatedClass($className);
    }
}
