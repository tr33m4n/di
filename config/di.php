<?php

use DanielDoyle\HappyDi\Container\PreferenceResolver;
use DanielDoyle\HappyDi\Container\ClassParameterResolver;

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
    PreferenceResolver::CONFIG_KEY => [],
    ClassParameterResolver::CONFIG_KEY => []
];
