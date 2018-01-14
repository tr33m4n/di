<?php

namespace HappyDi\Helpers;

use HappyUtilities\Helpers\Config as UtilityConfig;

/**
 * Class Config
 *
 * @package HappyDi\Helpers
 *
 * @author  Daniel Doyle <dd@amp.co>
 */
class Config extends UtilityConfig
{
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
}
