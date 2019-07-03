<?php

namespace DanielDoyle\HappyDi\Container;

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
     * Resolve class preference
     *
     * @throws \DanielDoyle\HappyUtilities\Exception\MissingConfigException
     * @throws \DanielDoyle\HappyUtilities\Exception\RegistryException
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
