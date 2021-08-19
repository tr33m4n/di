<?php

declare(strict_types=1);

namespace tr33m4n\Di\Container;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use tr33m4n\Utilities\Config\ConfigCollection;
use tr33m4n\Utilities\Exception\ConfigException;

/**
 * Class GetParameters
 *
 * @package tr33m4n\Di\Container
 */
class GetParameters
{
    /**
     * Parameters config key
     */
    public const CONFIG_KEY = 'parameters';

    /**
     * Get class parameters by merging reflected parameters with config
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @param \ReflectionClass<object> $reflectionClass
     * @param array<string, mixed>     $parameters
     * @return array<int|string, mixed>
     */
    public function execute(ReflectionClass $reflectionClass, array $parameters = []): array
    {
        $classConstructor = $reflectionClass->getConstructor();
        if (!$classConstructor instanceof ReflectionMethod) {
            return [];
        }

        $convertedParameters = $this->convertParametersToValues($classConstructor->getParameters());

        try {
            $classConfig = config('di')->get(self::CONFIG_KEY)->get($reflectionClass->getName());
            if (!$classConfig instanceof ConfigCollection) {
                throw new ConfigException();
            }
        } catch (ConfigException $configException) {
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
                    case $parameter->isVariadic():
                        $converted[$parameter->getName()] = [];
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
