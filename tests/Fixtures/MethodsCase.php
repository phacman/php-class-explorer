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

abstract class MethodsCase
{
    /* print function comment */
    // some function comment
    // again function comment
    /** funny function comment */
    function pub0(): int
    {
        return 1;
    }

    public function pub1(): int
    {
        return 1;
    }

    public abstract function pub2(): int;

    public final function pub3(): int
    {
        return 1;
    }

    public static function pub4(): int
    {
        return 1;
    }

    abstract public function pub5(): int;

    final public function pub6(): int
    {
        return 1;
    }

    static public function pub7(): int
    {
        return 1;
    }

    protected function pro1(): int
    {
        return 1;
    }

    protected abstract function pro2(): int;

    protected final function pro3(): int
    {
        return 1;
    }

    protected static function pro4(): int
    {
        return 1;
    }

    abstract protected function pro5(): int;

    final protected function pro6(): int
    {
        return 1;
    }

    static protected function pro7(): int
    {
        return 1;
    }

    private function pri1(): int
    {
        return 1;
    }

    private static function pri4(): int
    {
        return 1;
    }

    static private function pri7(): int
    {
        return 1;
    }
}
