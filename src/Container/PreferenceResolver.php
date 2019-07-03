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
     * @var \DanielDoyle\HappyUtilities\Config\ConfigItem
     */
    private $diConfig;

    /**
     * PreferenceResolver constructor.
     *
     * @throws \DanielDoyle\HappyUtilities\Exception\MissingConfigException
     * @throws \DanielDoyle\HappyUtilities\Exception\RegistryException
     */
    public function __construct()
    {
        $this->diConfig = config()->get('di');
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
