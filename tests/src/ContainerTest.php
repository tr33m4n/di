<?php

namespace tr33m4n\Di\Tests;

use PHPUnit\Framework\TestCase;
use tr33m4n\Di\Exception\MissingClassException;
use tr33m4n\Di\Tests\Fixtures;
use tr33m4n\Utilities\Exception\RegistryException;
use tr33m4n\Utilities\Registry;

/**
 * Class ContainerTest
 *
 * @package tr33m4n\Di\Tests
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
            define('ROOT_CONFIG_PATH', __DIR__ . '/../fixtures/config');
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
     * Assert that the container throws error if class name is invalid or does not exist
     * 
     * @test
     * @throws \ReflectionException
     * @throws MissingClassException
     * @throws RegistryException
     * @return void
     */
    public function assertContainerThrowsError() : void
    {
        $this->expectException(MissingClassException::class);
        di()->get('abcd');
    }

    /**
     * Valid data provider
     * 
     * @return array
     */
    public function validDataProvider() : array
    {
        return [
            [
                Registry::class,
                new Registry()
            ],
            [
                Fixtures\BasicInstantiation\ParentClass::class,
                new Fixtures\BasicInstantiation\ParentClass(
                    new Fixtures\BasicInstantiation\FirstChildClass(
                        new Fixtures\BasicInstantiation\SecondChildClass(
                            new Fixtures\BasicInstantiation\ThirdChildClass(),
                            new Fixtures\BasicInstantiation\ThirdChildClass2(),
                            new Fixtures\BasicInstantiation\ThirdChildClass3()
                        ),
                        new Fixtures\BasicInstantiation\SecondChildClass2(
                            new Fixtures\BasicInstantiation\ThirdChildClass(),
                            new Fixtures\BasicInstantiation\ThirdChildClass2(),
                            new Fixtures\BasicInstantiation\ThirdChildClass3()
                        ),
                        new Fixtures\BasicInstantiation\SecondChildClass3(
                            new Fixtures\BasicInstantiation\ThirdChildClass(),
                            new Fixtures\BasicInstantiation\ThirdChildClass2(),
                            new Fixtures\BasicInstantiation\ThirdChildClass3()
                        )
                    ),
                    new Fixtures\BasicInstantiation\FirstChildClass2(
                        new Fixtures\BasicInstantiation\SecondChildClass(
                            new Fixtures\BasicInstantiation\ThirdChildClass(),
                            new Fixtures\BasicInstantiation\ThirdChildClass2(),
                            new Fixtures\BasicInstantiation\ThirdChildClass3()
                        ),
                        new Fixtures\BasicInstantiation\SecondChildClass2(
                            new Fixtures\BasicInstantiation\ThirdChildClass(),
                            new Fixtures\BasicInstantiation\ThirdChildClass2(),
                            new Fixtures\BasicInstantiation\ThirdChildClass3()
                        ),
                        new Fixtures\BasicInstantiation\SecondChildClass3(
                            new Fixtures\BasicInstantiation\ThirdChildClass(),
                            new Fixtures\BasicInstantiation\ThirdChildClass2(),
                            new Fixtures\BasicInstantiation\ThirdChildClass3()
                        )
                    ),
                    new Fixtures\BasicInstantiation\FirstChildClass3(
                        new Fixtures\BasicInstantiation\SecondChildClass(
                            new Fixtures\BasicInstantiation\ThirdChildClass(),
                            new Fixtures\BasicInstantiation\ThirdChildClass2(),
                            new Fixtures\BasicInstantiation\ThirdChildClass3()
                        ),
                        new Fixtures\BasicInstantiation\SecondChildClass2(
                            new Fixtures\BasicInstantiation\ThirdChildClass(),
                            new Fixtures\BasicInstantiation\ThirdChildClass2(),
                            new Fixtures\BasicInstantiation\ThirdChildClass3()
                        ),
                        new Fixtures\BasicInstantiation\SecondChildClass3(
                            new Fixtures\BasicInstantiation\ThirdChildClass(),
                            new Fixtures\BasicInstantiation\ThirdChildClass2(),
                            new Fixtures\BasicInstantiation\ThirdChildClass3()
                        )
                    )
                )
            ]
        ];
    }
}
