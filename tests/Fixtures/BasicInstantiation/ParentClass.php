<?php

namespace tr33m4n\Di\Tests\Fixtures\BasicInstantiation;

class ParentClass
{
    private $firstChildClass;

    private $firstChildClass2;

    private $firstChildClass3;

    public function __construct(
        FirstChildClass $firstChildClass,
        FirstChildClass2 $firstChildClass2,
        FirstChildClass3 $firstChildClass3
    ) {
        $this->firstChildClass = $firstChildClass;
        $this->firstChildClass2 = $firstChildClass2;
        $this->firstChildClass3 = $firstChildClass3;
    }
}
