<?php

/*
 * This file is part of ClassExplorer package.
 *
 * (c) Pavel Vasin <phacman@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'get_class_name' => 'ShortBaseCase',
    'get_class_type' => 'class',
    'get_constants' => [
        "const HELLO = 'world'",
        'const HELLO_ON_ARRAY = [self::HELLO]',
    ],
    'get_enum_cases' => [],
    'get_extends' => 'UseIt',
    'get_full_class_name' => 'class ShortBaseCase',
    'get_implements' => [
        'FirstInterface',
    ],
    'get_imports' => [
        'PhacMan\ClassExplorer\Tests\Fixtures\Nested\FirstInterface',
        'PhacMan\ClassExplorer\Tests\Fixtures\Nested\UseIt',
    ],
    'get_lines_count' => 50,
    'get_methods' => [
        'public function __construct()',
        'public function getPublic(): string',
        'protected function getProtected(): array',
    ],
    'get_namespace' => 'PhacMan\ClassExplorer\Tests\Fixtures',
    'get_properties' => [
        'private array $itemsEmpty = []',
        'protected string $info = \'info message\'',
    ],
    'get_qualified_name' => '\PhacMan\ClassExplorer\Tests\Fixtures\ShortBaseCase',
    'has_abstract_methods' => false,
    'has_constructor' => true,
    'is_abstract' => false,
    'is_atypical_class' => false,
    'is_class' => true,
    'is_enum' => false,
    'is_explicit_abstract' => false,
    'is_final' => false,
    'is_implicit_abstract' => false,
    'is_interface' => false,
    'is_readonly' => false,
    'is_trait' => false,
];
