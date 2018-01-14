<?php

namespace HappyDi;

use HappyDi\Helpers\Config;
use HappyDi\Exceptions\MissingClassException;
use HappyUtilities\Exceptions\MissingConfigException;

/**
 * Class Di
 *
 * @package HappyDi
 *
 * @author  Daniel Doyle <dd@amp.co>
 */
class Di
{
    /**
     * @var \HappyUtilities\Data\DataObject|null
     */
    protected $config = null;

    /**
     * @var array
     */
    protected $sharedInstantiatedClasses = [];

    /**
     * Di constructor.
     */
    public function __construct()
    {
        $this->config = $this->getConfig();
    }

    /**
     * Get class from DI
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $className Class name to get
     * @throws \Exception
     * @throws \HappyDi\Exceptions\MissingClassException
     * @return object
     */
    public function get(string $className)
    {
        // Early return the instantiated class if it's found in the shared array
        if (array_key_exists($className, $this->sharedInstantiatedClasses)) {
            return $this->sharedInstantiatedClasses[$className];
        }

        // Make sure class exists
        if (!class_exists($className)) {
            throw new MissingClassException(sprintf('%s does not exist!', $className));
        }

        // Get params from config, return class string if entry has no params
        if (!$paramsFromConfig = $this->config->get($className)) {
            throw new MissingConfigException(sprintf('%s does not exist in the DI config!', $className));
        }

        // Instantiate class from DI
        return $this->getInstantiatedClass($className, is_array($paramsFromConfig) ? $paramsFromConfig : []);
    }

    /**
     * Return instantiated class
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $className  Class name
     * @param array  $parameters Constructor parameters
     * @return object
     */
    protected function getInstantiatedClass(string $className, array $parameters = [])
    {
        // Check whether class should be added to the shared instantiated classes array
        $isShared = isset($parameters['shared']) ?: false;

        /**
         * Check if class to instantiate has the arguments parameter. If it does not, instantiate without passing
         * any constructor args, adding to the shared instance array if applicable
         */
        if (!array_key_exists('arguments', $parameters) || empty($parameters['arguments'])) {
            return $this->instantiateClass($className, $isShared);
        }

        /**
         * Loop through the constructor arguments and call get() on each argument, adding them to the constructor
         * argument array. Once all arguments have been instantiated, instantiated the parent class with the
         * arguments, checking if the class should be added to the shared array
         */
        $instantiatedConstructorArguments = [];
        foreach ($parameters['arguments'] as $constructorArgument) {
            $instantiatedConstructorArguments[] = $this->get($constructorArgument);
        }

        return $this->instantiateClassWithConstructorArgs($className, $instantiatedConstructorArguments, $isShared);
    }

    /**
     * Add instantiated class to shared array
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $className         Class name
     * @param object $instantiatedClass Instantiated class
     * @return void
     */
    protected function setShared(string $className, $instantiatedClass)
    {
        $this->sharedInstantiatedClasses[$className] = $instantiatedClass;
    }

    /**
     * Get instantiated class from shared array
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $className Class name
     * @return object
     */
    protected function getShared(string $className)
    {
        return $this->sharedInstantiatedClasses[$className];
    }

    /**
     * Instantiate new class
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $className Class name
     * @param bool   $shared    Whether class should be shared or not
     * @return object
     */
    protected function instantiateClass(string $className, bool $shared = null)
    {
        $instantiatedClass = new $className();

        if (!$shared) {
            return $instantiatedClass;
        }

        $this->setShared($className, $instantiatedClass);

        return $this->getShared($className);
    }

    /**
     * Instantiate new class with constructor arguments
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $className       Class name
     * @param array  $constructorArgs Array of constructor arguments
     * @param bool   $shared          Whether class should be shared or not
     * @return object
     */
    protected function instantiateClassWithConstructorArgs(
        string $className,
        array $constructorArgs,
        bool $shared = null
    ) {
        // Use reflection class to pass constructor args to new instance
        $instantiatedClass = (new \ReflectionClass($className))
            ->newInstanceArgs($constructorArgs);

        if (!$shared) {
            return $instantiatedClass;
        }

        $this->setShared($className, $instantiatedClass);

        return $this->getShared($className);
    }

    /**
     * Get config
     *
     * @author Daniel Doyle <dd@amp.co>
     * @return \HappyDi\Helpers\Config
     */
    protected function getConfig() : Config
    {
        return new Config();
    }
}
