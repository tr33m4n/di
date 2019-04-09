<?php

namespace DanielDoyle\HappyDi\Container;

use DanielDoyle\HappyUtilities\Helpers\ConfigProvider;

/**
 * Class PreferenceResolver
 *
 * @package DanielDoyle\HappyDi\Container
 */
class PreferenceResolver
{
    /**
     * Preferences config key
     */
    const CONFIG_KEY = 'preferences';

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
     * Resolve class preference
     *
     * @param string $className Class/interface name
     * @return string
     */
    public function resolve(string $className) : string
    {
        $preferencesConfig = $this->configProvider->get(self::CONFIG_KEY);
        if (array_key_exists($className, $preferencesConfig) && !empty($preferencesConfig[$className])) {
            return $preferencesConfig[$className];
        }

        return $className;
    }
}
