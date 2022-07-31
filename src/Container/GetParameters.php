<?php

declare(strict_types=1);

namespace tr33m4n\Di\Container;

use Closure;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use tr33m4n\Di\Config;
use tr33m4n\Di\Config\ConfigCollection;
use tr33m4n\Di\Exception\ConfigException;

final class GetParameters
{
    public function __construct(
        private readonly Config $config
    ) {
    }

    /**
     * Get class parameters by merging reflected parameters with config
     *
     * @throws \ReflectionException
     * @param \ReflectionClass<object> $reflectionClass
     * @param array<string, mixed>     $parameters
     * @return array<int|string, mixed>
     */
    public function execute(ReflectionClass $reflectionClass, array $parameters = []): array
    {
        $reflectionMethod = $reflectionClass->getConstructor();
        if (!$reflectionMethod instanceof ReflectionMethod) {
            return [];
        }

        $convertedParameters = $this->convertParametersToValues($reflectionMethod->getParameters());

        try {
            $classConfig = $this->config->getParameters()->get($reflectionClass->getName());
            if (!$classConfig instanceof ConfigCollection) {
                return $convertedParameters;
            }
        } catch (ConfigException) {
            return $convertedParameters;
        }

        return array_merge(
            array_map(
                $this->getParameterIteratee($classConfig),
                $convertedParameters,
                array_keys($convertedParameters)
            ),
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
            static function (array $converted, ReflectionParameter $reflectionParameter): array {
                $reflectionType = $reflectionParameter->getType();

                $converted[$reflectionParameter->getName()] = match (true) {
                    $reflectionType instanceof ReflectionNamedType => $reflectionType->getName(),
                    $reflectionParameter->isDefaultValueAvailable() => $reflectionParameter->getDefaultValue(),
                    $reflectionParameter->isVariadic() => [],
                    default => null,
                };

                return $converted;
            },
            []
        );
    }

    /**
     * Get parameter iteratee
     */
    private function getParameterIteratee(ConfigCollection $configCollection): Closure
    {
        return static fn ($parameterValue, string $parameterName) => $configCollection->has($parameterName)
            ? $configCollection->get($parameterName)
            : $parameterValue;
    }
}
