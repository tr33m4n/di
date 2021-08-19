<?php

declare(strict_types=1);

namespace tr33m4n\Di\Tests;

use PHPUnit\Framework\TestCase;
use tr33m4n\Di\Exception\MissingClassException;
use tr33m4n\Di\Tests\Fixtures;
use tr33m4n\Utilities\Data\DataCollection;

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
    public function setUp(): void
    {
        if (!defined('ROOT_CONFIG_PATH')) {
            define('ROOT_CONFIG_PATH', __DIR__ . '/Fixtures/config');
        }
    }

    /**
     * Assert that the container returns requested class
     *
     * @test
     * @dataProvider validDataProvider
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param string $input
     * @param object $expected
     * @return void
     */
    public function assertContainerReturnsRequestedClass(string $input, object $expected): void
    {
        $this->assertEquals(container()->get($input), $expected);
    }

    /**
     * Assert that the container throws error if class name is invalid or does not exist
     *
     * @test
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @return void
     */
    public function assertContainerThrowsError(): void
    {
        $this->expectException(MissingClassException::class);
        container()->get('abcd');
    }

    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            [
                DataCollection::class,
                new DataCollection()
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
