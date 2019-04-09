<?php

namespace DanielDoyle\HappyDi\Container;

use DanielDoyle\HappyUtilities\Helpers\ConfigProvider;

/**
 * Class ClassParameterResolver
 *
 * @package DanielDoyle\HappyDi\Container
 */
class ClassParameterResolver
{
    /**
     * Parameters config key
     */
    const CONFIG_KEY = 'parameters';

    /**
     * @var \DanielDoyle\HappyUtilities\Helpers\ConfigProvider
     */
    private $configProvider;

    /**
     * ClassParameterResolver constructor.
     *
     * @param \DanielDoyle\HappyUtilities\Helpers\ConfigProvider $configProvider
     */
    public function __construct(
        ConfigProvider $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    /**
     * Resolve class parameters by merging reflected parameters with config
     *
     * @param \ReflectionClass $reflectionClass
     * @return array
     */
    public function resolve(\ReflectionClass $reflectionClass) : array
    {
        $classConstructor = $reflectionClass->getConstructor();
        $resolvedParameters = [];

        if (!$classConstructor) {
            return $resolvedParameters;
        }

        $parametersConfig = $this->configProvider->get(self::CONFIG_KEY);
        $classConfig = isset($parametersConfig[$reflectionClass->getName()]) ?: [];
        $classParameters = $classConstructor->getParameters();

        foreach ($classParameters as $classParameter) {
            switch (true) {
                case array_key_exists($classParameter->getName(), $classConfig):
                    $resolvedParameters[] = $classConfig[$classParameter->getName()];
                    break;
                case $classParameter->getClass() :
                    $resolvedParameters[] = (string) $classParameter->getType();
                    break;
                case $classParameter->isDefaultValueAvailable() :
                    $resolvedParameters[] = $classParameter->getDefaultValue();
                    break;
                default :
                    $resolvedParameters[] = null;
            }
        }

        return $resolvedParameters;
    }
}
