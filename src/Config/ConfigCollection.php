<?php

declare(strict_types=1);

namespace tr33m4n\Di\Config;

use tr33m4n\Di\Exception\ConfigException;

final class ConfigCollection
{
    /**
     * @var array<string, mixed>
     */
    private readonly array $config;

    /**
     * ConfigCollection constructor.
     *
     * @param array<string, mixed> $configArray
     */
    public function __construct(array $configArray = [])
    {
        $this->config = array_map(static function ($configValue) {
            // Iterate config array and populate with new collection if is an array
            return is_array($configValue) ? new self($configValue) : $configValue;
        }, $configArray);
    }

    /**
     * Check if config exists by key
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * Get config by key
     *
     * @throws \tr33m4n\Di\Exception\ConfigException
     * @return mixed|null
     */
    public function get(string $key): mixed
    {
        if (!$this->has($key)) {
            throw new ConfigException(sprintf('Config key "%s" does not exist', $key));
        }

        return $this->config[$key] ?? null;
    }
}
