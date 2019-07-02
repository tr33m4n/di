<?php

namespace DanielDoyle\HappyDi;

use PHPUnit\Framework\TestCase;
use DanielDoyle\HappyDi\Exception\MissingClassException;
use DanielDoyle\HappyUtilities\Config\ConfigProvider;
use DanielDoyle\HappyUtilities\Registry;

/**
 * ContainerTest class
 */
final class ContainerTest extends TestCase
{
    /**
     * @var \DanielDoyle\HappyDi\Container
     */
    private $container;

    /**
     * Setup test
     *
     * @return void
     */
    public function setUp() : void
    {
        $diConfig = new ConfigProvider([__DIR__ . '/../config']);

        $this->container = new Container(
            new Container\ClassParameterResolver($diConfig),
            new Container\PreferenceResolver($diConfig)
        );
    }

    /**
     * Assert that the container returns requested class
     * 
     * @test
     * @dataProvider validDataProvider
     * @throws \ReflectionException
     * @throws MissingClassException
     * @param string $input
     * @param object $expected
     * @return void
     */
    public function assertContainerReturnsRequestedClass($input, $expected)
    {
        $this->assertEquals($this->container->get($input), $expected);
    }

    /**
     * Assert that the container throws error
     * 
     * @test
     * @dataProvider invalidDataProvider
     * @throws \ReflectionException
     * @throws MissingClassException
     * @param string $input
     * @return void
     */
    public function assertContainerThrowsError($input)
    {
        $this->expectException(MissingClassException::class);
        $this->container->get($input);
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
