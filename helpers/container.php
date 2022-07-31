<?php

declare(strict_types=1);

use tr33m4n\Di\Container;
use tr33m4n\Di\Config;

if (!function_exists('container')) {
    /**
     * Helper function for easily accessing the DI container
     *
     * @param \tr33m4n\Di\Config|null $config
     * @return \tr33m4n\Di\Container
     */
    function container(Config $config = null): Container
    {
        static $container = null;
        if ($container instanceof Container) {
            return $container;
        }

        $config = $config ?? new Config();

        return $container = new Container(
            new Container\GetParameters($config),
            new Container\GetPreference($config)
        );
    }
}
