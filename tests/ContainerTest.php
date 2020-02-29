<?php

namespace tr33m4n\Di;

use PHPUnit\Framework\TestCase;
use tr33m4n\Di\Exception\MissingClassException;
use tr33m4n\Utilities\Exception\RegistryException;
use tr33m4n\Utilities\Exception\MissingConfigException;
use tr33m4n\Utilities\Registry;

/**
 * ContainerTest class
 */
final class ContainerTest extends TestCase
{
    /**
     * Setup test
     *
     * @return void
     */
    public function setUp() : void
    {
        if (!defined('ROOT_CONFIG_PATH')) {
            define('ROOT_CONFIG_PATH', __DIR__ . '/../config');
        }
    }

    /**
     * Assert that the container returns requested class
     * 
     * @test
     * @dataProvider validDataProvider
     * @throws \ReflectionException
     * @throws MissingClassException
     * @throws RegistryException
     * @param string $input
     * @param object $expected
     * @return void
     */
    public function assertContainerReturnsRequestedClass($input, $expected) : void
    {
        $this->assertEquals(di()->get($input), $expected);
    }

    /**
     * Assert that the container throws error
     * 
     * @test
     * @dataProvider invalidDataProvider
     * @throws \ReflectionException
     * @throws MissingClassException
     * @throws RegistryException
     * @param string $input
     * @return void
     */
    public function assertContainerThrowsError($input) : void
    {
        $this->expectException(MissingClassException::class);
        di()->get($input);
    }

    /**
     * Valid data provider
     * 
     * @return array
     */
    public function validDataProvider() : array
    {
        return [
            [Registry::class, new Registry()]
        ];
    }

    /**
     * Invalid data provider
     * 
     * @return array
     */
    public function invalidDataProvider() : array
    {
        return [
            ['abcd']
        ];
    }
}
