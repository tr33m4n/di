<?php

declare(strict_types=1);

namespace tr33m4n\Di\Container;

/**
 * Class IsShared
 *
 * @package tr33m4n\Di\Container
 */
class IsShared
{
    public const CONFIG_KEY = 'shared';

    /**
     * Is shared
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param string $className Class/interface name
     * @return bool
     */
    public function execute(string $className): bool
    {
        $isShared = config('di')->get(self::CONFIG_KEY)->get($className);

        return $isShared === null || $isShared;
    }
}
