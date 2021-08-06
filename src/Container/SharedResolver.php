<?php

declare(strict_types=1);

namespace tr33m4n\Di\Container;

/**
 * Class SharedResolver
 *
 * @package tr33m4n\Di\Container
 */
class SharedResolver
{
    /**
     * Shared config key
     */
    public const CONFIG_KEY = 'shared';

    /**
     * Resolve shared
     *
     * @throws \tr33m4n\Utilities\Exception\RegistryException
     * @param string $className Class/interface name
     * @return bool
     */
    public function resolve(string $className): bool
    {
        $isShared = config('di')->get(self::CONFIG_KEY)->get($className);

        return $isShared === null || $isShared;
    }
}
