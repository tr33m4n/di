<?php

declare(strict_types=1);

namespace tr33m4n\Di\Container;

/**
 * Class GetPreference
 *
 * @package tr33m4n\Di\Container
 */
class GetPreference
{
    public const CONFIG_KEY = 'preferences';

    /**
     * Get class preference
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param string $className Class/interface name
     * @return string
     */
    public function execute(string $className): string
    {
        return config('di')->get(self::CONFIG_KEY)->get($className) ?: $className;
    }
}
