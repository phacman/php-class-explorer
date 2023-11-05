<?php

/*
 * This file is part of ClassExplorer package.
 *
 * (c) Pavel Vasin <phacman@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhacMan\ClassExplorer;

use PhacMan\ClassExplorer\Contract\ConverterInterface;
use PhacMan\ClassExplorer\Contract\GetterInterface;
use PhacMan\ClassExplorer\Contract\IsHasInterface;

interface ExplorerInterface extends ConverterInterface, GetterInterface, IsHasInterface
{
}
