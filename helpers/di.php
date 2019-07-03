<?php

use DanielDoyle\HappyDi\Container;
use DanielDoyle\HappyUtilities\Registry;

/**
 * Helper function for easily accessing the DI container
 *
 * @throws \DanielDoyle\HappyUtilities\Exception\MissingConfigException
 * @throws \DanielDoyle\HappyUtilities\Exception\RegistryException
 * @param array $additionalConfigPaths Additional config paths
 * @return \DanielDoyle\HappyDi\Container|mixed|null
 */
function di(array $additionalConfigPaths = [])
{
    if ($registeredContainer = Registry::get('container')) {
        return $registeredContainer;
    }

    $config = config($additionalConfigPaths);
    $container = new Container(
        new Container\ClassParameterResolver($config),
        new Container\PreferenceResolver($config),
        new Container\SharedResolver($config)
    );

    Registry::set('container', $container);

    return $container;
}
