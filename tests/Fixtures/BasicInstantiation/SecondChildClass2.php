<?php

declare(strict_types=1);

namespace tr33m4n\Di\Tests\Fixtures\BasicInstantiation;

class SecondChildClass2
{
    private ThirdChildClass $thirdChildClass;

    private ThirdChildClass2 $thirdChildClass2;

    private ThirdChildClass3 $thirdChildClass3;

    public function __construct(
        ThirdChildClass $thirdChildClass,
        ThirdChildClass2 $thirdChildClass2,
        ThirdChildClass3 $thirdChildClass3
    ) {
        $this->thirdChildClass = $thirdChildClass;
        $this->thirdChildClass2 = $thirdChildClass2;
        $this->thirdChildClass3 = $thirdChildClass3;
    }
}
