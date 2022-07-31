<?php

declare(strict_types=1);

namespace tr33m4n\Di\Tests\Fixtures\BasicInstantiation;

class ParentClass
{
    private FirstChildClass $firstChildClass;

    private FirstChildClass2 $firstChildClass2;

    private FirstChildClass3 $firstChildClass3;

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
