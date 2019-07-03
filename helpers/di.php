<?php

use DanielDoyle\HappyDi\Container;
use DanielDoyle\HappyUtilities\Registry;

/**
 * Helper function for easily accessing the DI container
 *
 * @throws \DanielDoyle\HappyUtilities\Exception\MissingConfigException
 * @throws \DanielDoyle\HappyUtilities\Exception\RegistryException
 * @return \DanielDoyle\HappyDi\Container|mixed|null
 */
function di()
{
    if ($registeredContainer = Registry::get('container')) {
        return $registeredContainer;
    }

    $container = new Container(
        new Container\ClassParameterResolver(),
        new Container\PreferenceResolver(),
        new Container\SharedResolver()
    );

    Registry::set('container', $container);

    return $container;
}
