<?php

namespace tr33m4n\HappyDi\Container;

/**
 * Class SharedResolver
 *
 * @package tr33m4n\HappyDi\Container
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
     * @throws \tr33m4n\HappyUtilities\Exception\MissingConfigException
     * @throws \tr33m4n\HappyUtilities\Exception\RegistryException
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
