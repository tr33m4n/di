<?php

declare(strict_types=1);

namespace tr33m4n\Di\Container;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use tr33m4n\Utilities\Config\ConfigCollection;

/**
 * Class ClassParameterResolver
 *
 * @package tr33m4n\Di\Container
 */
class ClassParameterResolver
{
    /**
     * Parameters config key
     */
    public const CONFIG_KEY = 'parameters';

    /**
     * Resolve class parameters by merging reflected parameters with config
     *
     * @throws \tr33m4n\Utilities\Exception\RegistryException
     * @throws \ReflectionException
     * @param \ReflectionClass     $reflectionClass
     * @param array<string, mixed> $parameters
     * @return array<int, mixed>
     */
    public function resolve(ReflectionClass $reflectionClass, array $parameters = []): array
    {
        $classConstructor = $reflectionClass->getConstructor();
        if (!$classConstructor instanceof ReflectionMethod) {
            return [];
        }

        $convertedParameters = $this->convertParametersToValues($classConstructor->getParameters());

        $classConfig = config('di')->get(self::CONFIG_KEY)->get($reflectionClass->getName());
        if (!$classConfig instanceof ConfigCollection) {
            return $convertedParameters;
        }

        return array_merge(
            array_map(static function ($parameterValue, string $parameterName) use ($classConfig) {
                return $classConfig->has($parameterName) ? $classConfig->get($parameterName) : $parameterValue;
            }, $convertedParameters, array_keys($convertedParameters)),
            $parameters
        );
    }

    /**
     * Convert parameters to values
     *
     * @throws \ReflectionException
     * @param \ReflectionParameter[] $parameters
     * @return array<int, mixed>
     */
    private function convertParametersToValues(array $parameters): array
    {
        return array_reduce(
            $parameters,
            static function (array $converted, ReflectionParameter $parameter): array {
                switch (true) {
                    case $parameter->getClass() !== null:
                        $converted[$parameter->getName()] = $parameter->getClass()->getName();
                        break;
                    case $parameter->isDefaultValueAvailable():
                        $converted[$parameter->getName()] = $parameter->getDefaultValue();
                        break;
                    default:
                        $converted[$parameter->getName()] = null;
                }

                return $converted;
            },
            []
        );
    }
}
