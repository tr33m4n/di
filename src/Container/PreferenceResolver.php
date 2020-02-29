<?php

namespace tr33m4n\HappyDi\Container;

/**
 * Class PreferenceResolver
 *
 * @package tr33m4n\HappyDi\Container
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
     * @throws \tr33m4n\HappyUtilities\Exception\MissingConfigException
     * @throws \tr33m4n\HappyUtilities\Exception\RegistryException
     * @param string $className Class/interface name
     * @return string
     */
    public function resolve(string $className) : string
    {
        $preferencesConfig = config('di')->get(self::CONFIG_KEY);
        if (array_key_exists($className, $preferencesConfig) && !empty($preferencesConfig[$className])) {
            return $preferencesConfig[$className];
        }

        return $className;
    }
}
