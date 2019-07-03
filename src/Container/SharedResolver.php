<?php

namespace DanielDoyle\HappyDi\Container;

use DanielDoyle\HappyUtilities\Config\ConfigProvider;

/**
 * Class SharedResolver
 *
 * @package DanielDoyle\HappyDi\Container
 */
class SharedResolver
{
    /**
     * Shared config key
     */
    const CONFIG_KEY = 'shared';

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
     * Resolve shared
     *
     * @param string $className Class/interface name
     * @return string
     */
    public function resolve(string $className) : string
    {
        $sharedConfig = $this->diConfig->get(self::CONFIG_KEY);
        return !array_key_exists($className, $sharedConfig)
            || (array_key_exists($className, $sharedConfig) && $sharedConfig[$className]);
    }
}
