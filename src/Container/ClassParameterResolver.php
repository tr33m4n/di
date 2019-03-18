<?php

namespace DanielDoyle\HappyDi\Container;

use HappyUtilities\Helpers\Config;

/**
 * Class ClassParameterResolver
 *
 * @package DanielDoyle\HappyDi\Container
 */
class ClassParameterResolver
{
    /**
     * @var \HappyUtilities\Helpers\Config
     */
    private $configProvider;

    /**
     * ClassParameterResolver constructor.
     *
     * @param \HappyUtilities\Helpers\Config $configProvider
     */
    public function __construct(
        Config $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    /**
     * Resolve class parameters by merging reflected parameters with config
     *
     * @author Daniel Doyle <dd@amp.co>
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

        $classConfig = $this->configProvider->get($reflectionClass->getName()) ?: [];
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
