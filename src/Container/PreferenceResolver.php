<?php

namespace DanielDoyle\HappyDi\Container;

use DanielDoyle\HappyUtilities\Config\ConfigProvider;

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
     * Resolve class preference
     *
     * @param string $className Class/interface name
     * @return string
     */
    public function resolve(string $className) : string
    {
        $preferencesConfig = $this->diConfig->get(self::CONFIG_KEY);
        if (array_key_exists($className, $preferencesConfig) && !empty($preferencesConfig[$className])) {
            return $preferencesConfig[$className];
        }

        return $className;
    }
}
