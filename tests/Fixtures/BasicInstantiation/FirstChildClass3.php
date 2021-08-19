<?php

namespace tr33m4n\Di\Tests\Fixtures\BasicInstantiation;

class FirstChildClass3
{
    private $secondChildClass;

    private $secondChildClass2;

    private $secondChildClass3;

    public function __construct(
        SecondChildClass $secondChildClass,
        SecondChildClass2 $secondChildClass2,
        SecondChildClass3 $secondChildClass3
    ) {
        $this->secondChildClass = $secondChildClass;
        $this->secondChildClass2 = $secondChildClass2;
        $this->secondChildClass3 = $secondChildClass3;
    }
}
