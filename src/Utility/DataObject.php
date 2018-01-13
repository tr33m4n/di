<?php

namespace HappyDi\Utility;

/**
 * Class DataObject
 *
 * @package HappyDi\Utility
 *
 * @author  Daniel Doyle <dd@amp.co>
 */
class DataObject implements \Iterator
{
    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Iterator constructor.
     *
     * @param array $dataArray Atomically add data on construct
     */
    public function __construct(array $dataArray = [])
    {
        if (!empty($dataArray)) {
            $this->setAll($dataArray);
        }
    }

    /**
     * Set data
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $key   Data key
     * @param mixed  $value Data value
     * @return $this
     */
    public function set(string $key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Get data
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $key Data to return by key
     * @return mixed
     */
    public function get(string $key)
    {
        if (!$this->has($key)) {
            return false;
        }

        return $this->data[$key];
    }

    /**
     * Check if key exists
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $key Key to check
     * @return bool
     */
    public function has(string $key) : bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Atomically set data array
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param array $dataArray Data array
     * @return $this
     */
    public function setAll(array $dataArray)
    {
        $this->data = $dataArray;

        return $this;
    }

    /**
     * Get all data
     *
     * @author Daniel Doyle <dd@amp.co>
     * @return array
     */
    public function getAll() : array
    {
        return $this->data;
    }

    /**
     * Reset array
     *
     * @author Daniel Doyle <dd@amp.co>
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * Return current index value
     *
     * @author Daniel Doyle <dd@amp.co>
     * @return mixed
     */
    public function current()
    {
        return $this->data[$this->index];
    }

    /**
     * Return current index
     *
     * @author Daniel Doyle <dd@amp.co>
     * @return int
     */
    public function key() : int
    {
        return $this->index;
    }

    /**
     * Increment index
     *
     * @author Daniel Doyle <dd@amp.co>
     * @return void
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * Check is valid
     *
     * @author Daniel Doyle <dd@amp.co>
     * @return bool
     */
    public function valid() : bool
    {
        return isset($this->data[$this->index]);
    }
}
