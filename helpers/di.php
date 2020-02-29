<?php

use tr33m4n\Di\Container;
use tr33m4n\Utilities\Registry;

/**
 * Helper function for easily accessing the DI container
 *
 * @throws \tr33m4n\Utilities\Exception\RegistryException
 * @return \tr33m4n\Di\Container
 */
function di() : Container
{
    if (($container = Registry::get('container')) instanceof Container) {
        return $container;
    }

    $container = new Container(
        new Container\ClassParameterResolver(),
        new Container\PreferenceResolver(),
        new Container\SharedResolver()
    );

    Registry::set('container', $container);

    return $container;
}
