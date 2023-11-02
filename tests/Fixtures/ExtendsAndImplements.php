<?php

/*
 * This file is part of ClassExplorer package.
 *
 * (c) Pavel Vasin <phacman@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhacMan\ClassExplorer\Tests\Fixtures;

use PhacMan\ClassExplorer\Tests\Fixtures\Nested\FirstInterface;
use PhacMan\ClassExplorer\Tests\Fixtures\Nested\SecondInterface;
use PhacMan\ClassExplorer\Tests\Fixtures\Nested\UseIt;

class ExtendsAndImplements extends UseIt implements FirstInterface, SecondInterface
{
}
