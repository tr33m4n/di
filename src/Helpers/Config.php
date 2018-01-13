<?php

namespace HappyDi\Helpers;

use HappyDi\Exceptions\MissingConfigException;
use HappyDi\Utility\DataObject;

/**
 * Class Config
 *
 * @package HappyDi\Helpers
 *
 * @author  Daniel Doyle <dd@amp.co>
 */
class Config extends DataObject
{
    /**
     * Config constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setConfig();
    }

    /**
     * Get class from config. If the key does not exist, flip the config and check again. This allows to return a class
     * name without any arguments associated with it
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $key Key to retrieve
     * @return bool|mixed|string
     */
    public function get(string $key)
    {
        if ($this->has($key)) {
            return parent::get($key);
        }

        // Flip config array, removing any values set as arrays
        $tempConfigArray = array_flip(array_filter($this->getAll(), function ($item) {
            return !is_array($item);
        }));

        // If still not present in config array, return false
        if (!isset($tempConfigArray[$key])) {
            return false;
        }

        // As the key is in the config, return value (class has no additional arguments to process and exists)
        return $key;
    }

    /**
     * Set config
     *
     * @author Daniel Doyle <dd@amp.co>
     * @throws \HappyDi\Exceptions\MissingConfigException
     * @return void
     */
    public function setConfig()
    {
        $configFiles = glob($this->getConfigPath() . '*.php');

        if (empty($configFiles)) {
            throw new MissingConfigException('There are no config files present in the config directory!');
        }

        // For each config file, merge into data array
        foreach ($configFiles as $configFilePath) {
            $this->setAll($this->getAll() + include $configFilePath);
        }
    }

    /**
     * Get config path
     *
     * @author Daniel Doyle <dd@amp.co>
     * @return string
     */
    protected function getConfigPath() : string
    {
        if (!defined('HAPPYDI_CONFIG_PATH')) {
            return dirname(__FILE__) . '/../../config/';
        }

        return rtrim(HAPPYDI_CONFIG_PATH, '/') . '/';
    }
}
