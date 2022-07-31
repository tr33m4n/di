<?php

declare(strict_types=1);

namespace tr33m4n\Di;

use tr33m4n\Di\Config\ConfigCollection;
use tr33m4n\Di\Config\FileAdapter;
use tr33m4n\Di\Config\FileAdapterInterface;
use tr33m4n\Di\Exception\ConfigException;

final class Config
{
    public const PREFERENCES_CONFIG_KEY = 'preferences';

    public const PARAMETERS_CONFIG_KEY = 'parameters';

    /**
     * @var array<string, \tr33m4n\Di\Config\ConfigCollection>
     */
    private array $config = [];

    /**
     * Config constructor.
     *
     * @throws \tr33m4n\Di\Exception\AdapterException
     * @param string[] $configPaths
     */
    public function __construct(
        private array $configPaths = [],
        private readonly FileAdapterInterface $fileAdapter = new FileAdapter()
    ) {
        $this->config = $this->processConfigPaths($this->initConfigPaths());
    }

    /**
     * Get preferences
     *
     * @throws \tr33m4n\Di\Exception\ConfigException
     */
    public function getPreferences(): ConfigCollection
    {
        return $this->getConfig(self::PREFERENCES_CONFIG_KEY);
    }

    /**
     * Get parameters
     *
     * @throws \tr33m4n\Di\Exception\ConfigException
     */
    public function getParameters(): ConfigCollection
    {
        return $this->getConfig(self::PARAMETERS_CONFIG_KEY);
    }

    /**
     * Get config by key
     *
     * @throws \tr33m4n\Di\Exception\ConfigException
     */
    private function getConfig(string $key): ConfigCollection
    {
        if (!array_key_exists($key, $this->config)) {
            throw new ConfigException('DI config has not been initialised');
        }

        return $this->config[$key];
    }

    /**
     * Process config path
     *
     * @throws \tr33m4n\Di\Exception\AdapterException
     * @return array<string, \tr33m4n\Di\Config\ConfigCollection>
     */
    private function processConfigPath(string $configPath): array
    {
        return array_reduce(
            glob(
                rtrim($configPath, DIRECTORY_SEPARATOR) // Sanitise config paths and append extension
                . DIRECTORY_SEPARATOR
                . FileAdapterInterface::FILE_NAME
            ) ?: [],
            function (array $mergedConfig, string $configFilePath): array {
                return array_map(
                    static fn ($configValue) => is_array($configValue)
                        ? new ConfigCollection($configValue)
                        : $configValue,
                    array_merge_recursive($mergedConfig, $this->fileAdapter->read($configFilePath))
                );
            },
            []
        );
    }

    /**
     * Process config paths
     *
     * @throws \tr33m4n\Di\Exception\AdapterException
     * @param string[] $configPaths
     * @return array<string, \tr33m4n\Di\Config\ConfigCollection>
     */
    private function processConfigPaths(array $configPaths): array
    {
        return array_reduce(
            $configPaths,
            function (array $configPaths, string $configPath): array {
                return array_merge_recursive($configPaths, $this->processConfigPath($configPath));
            },
            []
        );
    }

    /**
     * Init config paths. Config preference is in the order of:
     *
     * 1. Global path
     * 2. Additional paths passed to the constructor
     *
     * @return string[]
     */
    private function initConfigPaths(): array
    {
        // Check if global path has been defined, and add to path array
        if (defined('ROOT_CONFIG_PATH')) {
            $this->configPaths[] = ROOT_CONFIG_PATH;
        }

        return $this->configPaths;
    }
}
