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

interface GetterInterface
{
    public function getLinesCount(): int;

    public function getNamespace(): string;

    public function getClassName(): string;

    public function getFullClassName(): string;

    public function getQualifiedName(): string;

    public function getClassType(): string;

    /**
     * @return array<string>
     */
    public function getImports(): array;

    public function getExtends(): string;

    /**
     * @return array<string>
     */
    public function getImplements(): array;

    /**
     * @return array<string>
     */
    public function getConstants(): array;

    /**
     * @return array<string>
     */
    public function getProperties(): array;

    /**
     * @return array<string>
     */
    public function getEnumCases(): array;

    /**
     * @return array<string>
     */
    public function getMethods(): array;
}
