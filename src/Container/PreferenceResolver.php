<?php

namespace tr33m4n\Di\Container;

/**
 * Class PreferenceResolver
 *
 * @package tr33m4n\Di\Container
 */
class PreferenceResolver
{
    /**
     * Preferences config key
     */
    const CONFIG_KEY = 'preferences';

    /**
     * Resolve class preference
     *
     * @throws \tr33m4n\Utilities\Exception\RegistryException
     * @param string $className Class/interface name
     * @return string
     */
    public function resolve(string $className) : string
    {
        return config('di')->get(self::CONFIG_KEY)->get($className) ?: $className;
    }
}
