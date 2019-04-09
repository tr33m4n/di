<?php

namespace DanielDoyle\HappyDi;

use PHPUnit\Framework\TestCase;
use DanielDoyle\HappyDi\Container;
use DanielDoyle\HappyDi\Exception\MissingClassException;

/**
 * ContainerTest class
 */
final class ContainerTest extends TestCase
{
    /**
     * Assert that the container returns requested class
     * 
     * @test
     * @dataProvider validDataProvider
     * @param string $input
     * @param object $expected
     * @return void
     */
    public function assertContainerReturnsRequestedClass($input, $expected)
    {
        $this->assertEquals((new Container())->get($input), $expected);
    }

    /**
     * Assert that the container throws error
     * 
     * @test
     * @dataProvider invalidDataProvider
     * @param string $input
     * @return void
     */
    public function assertContainerThrowsError($input)
    {
        $this->expectException(MissingClassException::class);
        (new Container())->get($input);
    }

    /**
     * Valid data provider
     * 
     * @return array
     */
    public function validDataProvider() : array
    {
        return [
            ['\DanielDoyle\HappyDi\Registry', new \DanielDoyle\HappyDi\Registry()]
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
