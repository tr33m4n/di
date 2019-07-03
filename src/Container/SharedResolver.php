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
     * @var \DanielDoyle\HappyUtilities\Config\ConfigItem
     */
    private $diConfig;

    /**
     * SharedResolver constructor.
     *
     * @throws \DanielDoyle\HappyUtilities\Exception\MissingConfigException
     * @throws \DanielDoyle\HappyUtilities\Exception\RegistryException
     */
    public function __construct()
    {
        $this->diConfig = config()->get('di');
    }

    /**
     * Resolve shared
     *
     * @param string $className Class/interface name
     * @return bool
     */
    public function resolve(string $className) : bool
    {
        $sharedConfig = $this->diConfig->get(self::CONFIG_KEY);
        return !array_key_exists($className, $sharedConfig)
            || (array_key_exists($className, $sharedConfig) && $sharedConfig[$className]);
    }
}
