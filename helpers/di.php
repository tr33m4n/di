<?php

use tr33m4n\HappyDi\Container;
use tr33m4n\HappyUtilities\Registry;

/**
 * Helper function for easily accessing the DI container
 *
 * @throws \tr33m4n\HappyUtilities\Exception\RegistryException
 * @return \tr33m4n\HappyDi\Container|mixed|null
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
