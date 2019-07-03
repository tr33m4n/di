<?php

namespace DanielDoyle\HappyDi\Container;

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
     * Resolve shared
     *
     * @throws \DanielDoyle\HappyUtilities\Exception\MissingConfigException
     * @throws \DanielDoyle\HappyUtilities\Exception\RegistryException
     * @param string $className Class/interface name
     * @return bool
     */
    public function resolve(string $className) : bool
    {
        $sharedConfig = config('di')->get(self::CONFIG_KEY);
        return !array_key_exists($className, $sharedConfig)
            || (array_key_exists($className, $sharedConfig) && $sharedConfig[$className]);
    }
}
