<?php

declare(strict_types=1);

namespace tr33m4n\Di\Container;

use tr33m4n\Di\Config;
use tr33m4n\Di\Exception\ConfigException;

final class GetPreference
{
    public const CONFIG_KEY = 'preferences';

    public function __construct(
        private readonly Config $config
    ) {
    }

    /**
     * Get class preference
     *
     * @param class-string $className
     * @return class-string
     */
    public function execute(string $className): string
    {
        try {
            $preference = $this->config->get(self::CONFIG_KEY)->get($className);
            if (!is_string($preference)) {
                return $className;
            }

            /** @var class-string $preference */
            return $preference;
        } catch (ConfigException) {
            return $className;
        }
    }
}
