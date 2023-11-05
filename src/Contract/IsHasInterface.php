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

interface IsHasInterface
{
    public function isAtypicalClass(): bool;

    public function isAbstract(): bool;

    public function isExplicitAbstract(): bool;

    public function isImplicitAbstract(): bool;

    public function isFinal(): bool;

    public function isReadonly(): bool;

    public function isClass(): bool;

    public function isInterface(): bool;

    public function isTrait(): bool;

    public function isEnum(): bool;

    public function hasConstructor(): bool;

    public function hasAbstractMethods(): bool;
}
