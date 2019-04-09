<?php

namespace DanielDoyle\HappyDi;

use DanielDoyle\HappyDi\Exception\RegistryException;

/**
 * Class Registry
 *
 * @package DanielDoyle\HappyDi
 */
class Registry
{
    /**
     * Container key
     */
    const CONTAINER_KEY = 'container';

    /**
     * @var array
     */
    private static $registry = [];

    /**
     * Whether key has been set
     *
     * @param mixed $key Key to check
     * @return bool
     */
    public static function has($key)
    {
        return isset(self::$registry[$key]);
    }

    /**
     * Set registry key
     *
     * @param string $key    Key to set
     * @param mixed  $value  Value to set
     * @throws \DanielDoyle\HappyDi\Exception\RegistryException
     */
    public static function set(string $key, $value)
    {
        if (self::has($key)) {
            throw new RegistryException('Registry key already exists!');
        }

        self::$registry[$key] = $value;
    }

    /**
     * Get registry value by key
     *
     * @param mixed $key Key to get
     * @return mixed|null
     */
    public static function get($key)
    {
        return self::has($key) ? self::$registry[$key] : null;
    }

    /**
     * Set container
     *
     * @param Container $container
     */
    public static function setContainer(Container $container)
    {
        self::set(self::CONTAINER_KEY, $container);
    }

    /**
     * Get container
     *
     * @throws \DanielDoyle\HappyDi\Exception\RegistryException
     * @return Container
     */
    public static function getContainer()
    {
        if (!self::has(self::CONTAINER_KEY)) {
            throw new RegistryException('Container has not been registered!');
        }

        return self::get(self::CONTAINER_KEY);
    }
}
