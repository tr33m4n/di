<?php

namespace DanielDoyle\HappyDi\Container;

use DanielDoyle\HappyUtilities\Config\ConfigProvider;

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
     * @var \DanielDoyle\HappyUtilities\Config\ConfigItem
     */
    private $diConfig;

    /**
     * ClassParameterResolver constructor.
     *
     * @param \DanielDoyle\HappyUtilities\Config\ConfigProvider $configProvider
     */
    public function __construct(
        ConfigProvider $configProvider
    ) {
        $this->diConfig = $configProvider->get('di');
    }

    /**
     * Resolve class parameters by merging reflected parameters with config
     *
     * @throws \ReflectionException
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

        $parametersConfig = $this->diConfig->get(self::CONFIG_KEY);
        $classConfig = $parametersConfig[$reflectionClass->getName()] ?? [];
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
