<?php

declare(strict_types=1);

use tr33m4n\Di\Container;

/**
 * Helper function for easily accessing the DI container
 *
 * @return \tr33m4n\Di\Container
 */
function di() : Container
{
    static $container = null;
    if ($container instanceof Container) {
        return $container;
    }

    return $container = new Container(
        new Container\ClassParameterResolver(),
        new Container\PreferenceResolver(),
        new Container\SharedResolver()
    );
}
