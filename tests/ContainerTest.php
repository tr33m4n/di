<?php

namespace DanielDoyle\HappyDi;

use PHPUnit\Framework\TestCase;
use DanielDoyle\HappyDi\Exception\MissingClassException;
use DanielDoyle\HappyUtilities\Exception\RegistryException;
use DanielDoyle\HappyUtilities\Exception\MissingConfigException;
use DanielDoyle\HappyUtilities\Registry;

/**
 * ContainerTest class
 */
final class ContainerTest extends TestCase
{
    /**
     * Setup test
     */
    public function setUp() : void
    {
        if (!defined('HAPPYUTILITIES_CONFIG_PATH')) {
            define('HAPPYUTILITIES_CONFIG_PATH', __DIR__ . '/../config');
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
     * @throws MissingConfigException
     * @param string $input
     * @param object $expected
     * @return void
     */
    public function assertContainerReturnsRequestedClass($input, $expected)
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
     * @throws MissingConfigException
     * @param string $input
     * @return void
     */
    public function assertContainerThrowsError($input)
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
            ['\DanielDoyle\HappyUtilities\Registry', new Registry()]
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
