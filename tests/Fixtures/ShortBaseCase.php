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
use PhacMan\ClassExplorer\Tests\Fixtures\Nested\UseIt;

class ShortBaseCase extends UseIt implements FirstInterface
{
    /** @var string common greeting */
    const HELLO = 'world'; // some one line comment
    const HELLO_ON_ARRAY = [self::HELLO];
    // some one line comment
    private array $itemsEmpty = [];

    /**
     * Some multiline description.
     * @var string
     */
    protected string $info = 'info message';

    public function __construct()
    {
        $this->line = UseIt::WORLD;
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
}
