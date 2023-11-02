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

use PhacMan\ClassExplorer\Tests\Fixtures\Nested\UseIt;

class BaseCase
{
    /** @var string common greeting */
    const HELLO = 'world'; // some one line comment
    const HELLO_ON_ARRAY = [self::HELLO];
    const HELLO_MU_ARRAY = [
        'one',
        'two',
        'three',
    ];
    // strange comment
    private string $line;
    // some one line comment
    private array $itemsEmpty = [];
    private array $itemsFiled = [1, 2, 3];
    private array $itemsFiledSecond = [
        'four',
        'five',
        'six',
    ];

    /**
     * Some multiline description.
     * @var string
     */
    protected string $info = 'info message';

    public function __construct()
    {
        $this->line = UseIt::WORLD;
    }

    // one line comment
    public static function getStaticPublic(): int
    {
        return 1;
    }

    public final function getFinalPublic(): int
    {
        return 1;
    }

    public static final function getStaticFinalPublic(): int
    {
        return 1;
    }

    public final static function getFinalStaticPublic(): int
    {
        return 1;
    }

    /** strange comment */
    public function getPublic(): string
    {
        return '1';
    }

    /**
     * Some description.
     * @return array
     */
    protected function getProtected(): array
    {
        return [];
    }

    private function getPrivate(): bool
    {
        return true;
    }
}
