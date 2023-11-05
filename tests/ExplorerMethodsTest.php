<?php

/*
 * This file is part of ClassExplorer package.
 *
 * (c) Pavel Vasin <phacman@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhacMan\ClassExplorer\Tests;

use PhacMan\ClassExplorer\Explorer;
use PHPUnit\Framework\TestCase;

class ExplorerMethodsTest extends TestCase
{
    /**
     * @covers \PhacMan\ClassExplorer\Explorer::isMethod
     * @return void
     */
    public function testIsMethod()
    {
        $path = __DIR__.'/Fixtures/MethodsCase.php';
        $class = new Explorer($path);
        $this->assertCount(18, $class->getMethods());
    }

    /**
     * @covers \PhacMan\ClassExplorer\Explorer::isAtypicalClass
     * @dataProvider atypicalClassDataProvider
     * @param  string $class
     * @param  bool   $expected
     * @return void
     */
    public function testAtypicalClass(string $class, bool $expected)
    {
        $path = __DIR__.'/Fixtures/'.$class;
        $class = new Explorer($path);
        $this->assertEquals($expected, $class->isAtypicalClass());
    }

    public static function atypicalClassDataProvider(): \Generator
    {
        yield [
            'class' => 'MoreThanOne.php',
            'expected' => true,
        ];

        yield [
            'class' => 'MoreThanOneClass.php',
            'expected' => true,
        ];

        yield [
            'class' => 'MoreThanOneEnum.php',
            'expected' => true,
        ];

        yield [
            'class' => 'MoreThanOneInterface.php',
            'expected' => true,
        ];

        yield [
            'class' => 'MoreThanOneTrait.php',
            'expected' => true,
        ];

        yield [
            'class' => 'NothingAtAll.php',
            'expected' => true,
        ];

        yield [
            'class' => 'BaseCase.php',
            'expected' => false,
        ];

        yield [
            'class' => 'OutputFormatterInterface.php',
            'expected' => false,
        ];

        yield [
            'class' => 'TesterTrait.php',
            'expected' => false,
        ];

        yield [
            'class' => 'ColorMode.php',
            'expected' => false,
        ];
    }

    /**
     * @covers \PhacMan\ClassExplorer\Explorer::getNamespace
     * @dataProvider hasNamespaceDataProvider
     * @param  string $class
     * @param  string $expected
     * @return void
     */
    public function testHasNamespace(string $class, string $expected)
    {
        $path = __DIR__.'/Fixtures/'.$class;
        $class = new Explorer($path);
        $this->assertEquals($expected, $class->getNamespace());
    }

    public static function hasNamespaceDataProvider(): \Generator
    {
        yield [
            'class' => 'BaseCase.php',
            'expected' => 'PhacMan\ClassExplorer\Tests\Fixtures',
        ];

        yield [
            'class' => 'NothingAtAll.php',
            'expected' => '',
        ];
    }

    /**
     * @covers \PhacMan\ClassExplorer\Explorer::getExtends
     * @dataProvider hasExtendsDataProvider
     * @param  string $class
     * @param  string $expected
     * @return void
     */
    public function testHasExtends(string $class, string $expected)
    {
        $path = __DIR__.'/Fixtures/'.$class;
        $class = new Explorer($path);
        $this->assertEquals($expected, $class->getExtends());
    }

    public static function hasExtendsDataProvider(): \Generator
    {
        yield [
            'class' => 'ExtendsAndImplements.php',
            'expected' => 'UseIt',
        ];

        yield [
            'class' => 'NothingAtAll.php',
            'expected' => '',
        ];
    }

    /**
     * @covers       \PhacMan\ClassExplorer\Explorer::getImplements
     * @dataProvider hasImplementsDataProvider
     * @param  string $class
     * @param  int    $expected
     * @return void
     */
    public function testHasImplements(string $class, int $expected)
    {
        $path = __DIR__.'/Fixtures/'.$class;
        $class = new Explorer($path);
        $this->assertCount($expected, $class->getImplements());
    }

    public static function hasImplementsDataProvider(): \Generator
    {
        yield [
            'class' => 'ExtendsAndImplements.php',
            'expected' => 2,
        ];

        yield [
            'class' => 'NothingAtAll.php',
            'expected' => 0,
        ];
    }

    /**
     * @covers       \PhacMan\ClassExplorer\Explorer::getImports
     * @dataProvider hasImportsDataProvider
     * @param  string $class
     * @param  int    $expected
     * @return void
     */
    public function testHasImports(string $class, int $expected)
    {
        $path = __DIR__.'/Fixtures/'.$class;
        $class = new Explorer($path);
        $this->assertCount($expected, $class->getImports());
    }

    public static function hasImportsDataProvider(): \Generator
    {
        yield [
            'class' => 'ConsoleDescriptor.php',
            'expected' => 7,
        ];

        yield [
            'class' => 'NothingAtAll.php',
            'expected' => 0,
        ];
    }

    public function testToArray(): void
    {
        $path = __DIR__.'/Fixtures/ShortBaseCase.php';
        $class = new Explorer($path);
        $expected = require __DIR__.'/data/toArray.php';
        $actual = $class->toArray();
        $this->assertEquals($expected, $actual);
    }

    public function testToString(): void
    {
        $path = __DIR__.'/Fixtures/ShortBaseCase.php';
        $class = new Explorer($path);
        $expected = file_get_contents(__DIR__.'/data/toString.txt');
        $actual = $class->__toString();
        $this->assertEquals($expected, $actual);
    }
}
