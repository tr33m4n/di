<?php
declare(strict_types=1);

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
    if (Registry::has(Container::REGISTRY_NAMESPACE)) {
        return Registry::get(Container::REGISTRY_NAMESPACE);
    }

    Registry::set(
        Container::REGISTRY_NAMESPACE,
        new Container(
            new Container\ClassParameterResolver(),
            new Container\PreferenceResolver(),
            new Container\SharedResolver()
        )
    );

    return Registry::get(Container::REGISTRY_NAMESPACE);
}
