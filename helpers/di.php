<?php

use DanielDoyle\HappyDi\Container;
use DanielDoyle\HappyUtilities\Registry;
use DanielDoyle\HappyUtilities\Config\ConfigProvider;

/**
 * Helper function for easily accessing the DI container
 *
 * @throws \DanielDoyle\HappyUtilities\Exception\RegistryException
 * @param array $additionalConfigPaths Additional paths
 * @return \DanielDoyle\HappyDi\Container|mixed|null
 */
function di(array $additionalConfigPaths = [])
{
    if ($registeredContainer = Registry::get('container')) {
        return $registeredContainer;
    }

    $diConfig = new ConfigProvider($additionalConfigPaths);
    $container = new Container(
        new Container\ClassParameterResolver($diConfig),
        new Container\PreferenceResolver($diConfig),
        new Container\SharedResolver($diConfig)
    );

    Registry::set('container', $container);

    return $container;
}
