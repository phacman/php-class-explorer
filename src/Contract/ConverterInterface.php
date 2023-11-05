<?php

/*
 * This file is part of ClassExplorer package.
 *
 * (c) Pavel Vasin <phacman@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhacMan\ClassExplorer\Contract;

interface ConverterInterface
{
    /**
     * @return array<string, string>
     */
    public function toArray(): array;

    public function __toString(): string;
}
