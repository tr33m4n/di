<?php

declare(strict_types=1);

namespace tr33m4n\Di;

use tr33m4n\Di\Config\FileAdapterInterface;
use tr33m4n\Di\Config\PhpFileAdapter;
use tr33m4n\Di\Config\ConfigCollection;

final class Config
{
    /**
     * @var array<string, \tr33m4n\Di\Config\ConfigCollection>
     */
    private array $config = [];

    /**
     * Config constructor.
     *
     * @throws \tr33m4n\Di\Exception\AdapterException
     * @param string[]                                $configPaths
     */
    public function __construct(
        private array $configPaths = [],
        private readonly FileAdapterInterface $fileAdapter = new PhpFileAdapter()
    ) {
        $this->initConfig();
    }

    /**
     * Get config by type
     */
    public function get(string $configPath): ConfigCollection
    {
        return $this->config[$configPath];
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
                . '*'
                . '.'
                . $this->fileAdapter::getFileExtension()
            ) ?: [],
            function (array $configFiles, string $configFilePath): array {
                $configFiles[basename($configFilePath, '.' . $this->fileAdapter::getFileExtension())] =
                    new ConfigCollection($this->fileAdapter->read($configFilePath));

                return $configFiles;
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
                return array_merge($configPaths, $this->processConfigPath($configPath));
            },
            []
        );
    }

    /**
     * Init config
     *
     * @throws \tr33m4n\Di\Exception\AdapterException
     */
    private function initConfig(): void
    {
        $this->config = $this->processConfigPaths($this->initConfigPaths());
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
