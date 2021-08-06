<?php
declare(strict_types=1);

use tr33m4n\Di\Container;

/**
 * Constructor args should be defined like the following:
 *
 * \Class\Namespace\Test2,
 * \Class\Namespace\Test3 => [
 *      'param1' => \Class\Namespace\Test4 => [
 *          'sub_param1' => \Class\Namespace\Test5
 *      ]
 * ],
 * \Class\Namespace\Test => [
 *      'param1' => \Class\Namespace\Test2,
 *      'param2' => \Class\Namespace\Test3
 */
return [
    Container\PreferenceResolver::CONFIG_KEY => [],
    Container\ClassParameterResolver::CONFIG_KEY => [],
    Container\SharedResolver::CONFIG_KEY => []
];
