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

use PhacMan\ClassExplorer\Exception\FileNotExistsException;
use PhacMan\ClassExplorer\Explorer;
use PHPUnit\Framework\TestCase;

class ExplorerCasesTest extends TestCase
{
    /**
     * @throws FileNotExistsException
     */
    public function testBaseCase()
    {
        $path = __DIR__.'/Fixtures/BaseCase.php';
        $class = new Explorer($path);

        $arrConstants = [
            'const HELLO = \'world\'',
            'const HELLO_ON_ARRAY = [self::HELLO]',
            'const HELLO_MU_ARRAY = [...]',
        ];

        $arrProperties = [
            'private string $line',
            'private array $itemsEmpty = []',
            'private array $itemsFiled = [1, 2, 3]',
            'private array $itemsFiledSecond = [...]',
            'protected string $info = \'info message\'',
        ];

        $arrMethods = [
            'public function __construct()',
            'public static function getStaticPublic(): int',
            'public final function getFinalPublic(): int',
            'public static final function getStaticFinalPublic(): int',
            'public final static function getFinalStaticPublic(): int',
            'public function getPublic(): string',
            'protected function getProtected(): array',
            'private function getPrivate(): bool',
        ];

        $this->assertEquals('', $class->getExtends());
        $this->assertEquals([], $class->getImplements());

        $this->assertEquals(['PhacMan\ClassExplorer\Tests\Fixtures\Nested\UseIt'], $class->getImports());
        $this->assertIsArray($class->getImports());

        $this->assertEquals($arrConstants, $class->getConstants());
        $this->assertEquals($arrProperties, $class->getProperties());
        $this->assertEquals($arrMethods, $class->getMethods());

        $this->assertEquals('\PhacMan\ClassExplorer\Tests\Fixtures\BaseCase', $class->getQualifiedName());
        $this->assertEquals('PhacMan\ClassExplorer\Tests\Fixtures', $class->getNamespace());
        $this->assertEquals('BaseCase', $class->getClassName());
        $this->assertEquals('class BaseCase', $class->getFullClassName());
        $this->assertEquals('class', $class->getClassType());

        $this->assertFalse($class->isAbstract());
        $this->assertFalse($class->isFinal());
        $this->assertFalse($class->isReadonly());

        $this->assertFalse($class->hasAbstractMethods());
        $this->assertTrue($class->hasConstructor());
        $this->assertTrue($class->isClass());
    }

    /**
     * @throws FileNotExistsException
     */
    public function testFinalCase()
    {
        $path = __DIR__.'/Fixtures/ConsoleEvents.php';
        $class = new Explorer($path);

        $arrConstants = [
            'public const COMMAND = \'console.command\'',
            'public const SIGNAL = \'console.signal\'',
            'public const TERMINATE = \'console.terminate\'',
            'public const ERROR = \'console.error\'',
            'public const ALIASES = [...]',
        ];

        $this->assertEquals($arrConstants, $class->getConstants());
        $this->assertIsArray($class->getImports());
        $this->assertFalse($class->hasConstructor());
        $this->assertTrue($class->isFinal());
        $this->assertTrue($class->isClass());
    }

    /**
     * @throws FileNotExistsException
     */
    public function testAbstractCase()
    {
        $path = __DIR__.'/Fixtures/ConsoleDescriptor.php';
        $class = new Explorer($path);
        $this->assertIsArray($class->getImports());
        $this->assertFalse($class->hasConstructor());
        $this->assertTrue($class->isImplicitAbstract());
        $this->assertTrue($class->isExplicitAbstract());
        $this->assertTrue($class->isAbstract());
        $this->assertCount(7, $class->getMethods());
        $this->assertTrue($class->isClass());
    }

    /**
     * @throws FileNotExistsException
     */
    public function testInterfaceCase()
    {
        $path = __DIR__.'/Fixtures/OutputFormatterInterface.php';
        $class = new Explorer($path);
        $this->assertTrue($class->isInterface());
        $this->assertCount(6, $class->getMethods());
    }

    /**
     * @throws FileNotExistsException
     */
    public function testTraitCase()
    {
        $path = __DIR__.'/Fixtures/TesterTrait.php';
        $class = new Explorer($path);

        $arrMethods = [
            'public function getDisplay(bool $normalize = false): string',
            'public function getErrorOutput(bool $normalize = false): string',
            'public function getInput(): InputInterface',
            'public function getOutput(): OutputInterface',
            'public function getStatusCode(): int',
            'public function assertCommandIsSuccessful(string $message = \'\'): void',
            'public function setInputs(array $inputs): static',
            'private function initOutput(array $options): void',
            'private static function createStream(array $inputs)',
        ];

        $this->assertEquals($arrMethods, $class->getMethods());
        $this->assertTrue($class->isTrait());
    }

    /**
     * @throws FileNotExistsException
     */
    public function testEnumCase()
    {
        $path = __DIR__.'/Fixtures/ColorMode.php';
        $class = new Explorer($path);

        $arrMethods = [
            'public function convertFromHexToAnsiColorCode(string $hexColor): string',
            'private function convertFromRGB(int $r, int $g, int $b): int',
            'private function degradeHexColorToAnsi4(int $r, int $g, int $b): int',
            'private function degradeHexColorToAnsi8(int $r, int $g, int $b): int',
        ];

        $this->assertEquals($arrMethods, $class->getMethods());
        $this->assertCount(3, $class->getEnumCases());
        $this->assertTrue($class->isEnum());
    }

    /**
     * @throws FileNotExistsException
     */
    public function testExtendsAndImplementsCase()
    {
        $path = __DIR__.'/Fixtures/ExtendsAndImplements.php';
        $class = new Explorer($path);
        $this->assertEquals('UseIt', $class->getExtends());
        $this->assertTrue((bool) \count($class->getImplements()));
    }

    /**
     * @throws FileNotExistsException
     */
    public function testExtendsNoImplementsCase()
    {
        $path = __DIR__.'/Fixtures/ExtendsNoImplements.php';
        $class = new Explorer($path);
        $this->assertEquals('UseIt', $class->getExtends());
        $this->assertFalse((bool) \count($class->getImplements()));
    }

    /**
     * @throws FileNotExistsException
     */
    public function testImplementsNoExtendsCase()
    {
        $path = __DIR__.'/Fixtures/ImplementsNoExtends.php';
        $class = new Explorer($path);
        $this->assertFalse((bool) $class->getExtends());
        $this->assertTrue((bool) \count($class->getImplements()));
    }

    public function testExceptionCase()
    {
        $this->expectException(FileNotExistsException::class);
        $this->expectExceptionMessage(Explorer::EXC_MESSAGE);
        $path = __DIR__.'/Fixtures/SomeWrongFile.php';
        $class = new Explorer($path);
    }
}
