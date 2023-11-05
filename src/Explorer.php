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

use PhacMan\ClassExplorer\Exception\FileNotExistsException;
use ReflectionMethod;

class Explorer implements ExplorerInterface
{
    const EXC_MESSAGE = 'There is no such file.';
    const TYPE_CLASS = 'class';
    const TYPE_INTERFACE = 'interface';
    const TYPE_TRAIT = 'trait';
    const TYPE_ENUM = 'enum';
    private array $main = [];
    private array $lines = [];
    private array $atypical = [];
    private array $constants = [];
    private array $properties = [];
    private array $methods = [];
    private array $cases = [];
    private array $classChunks = [];
    private string $namespace = '';
    private string $classHead = '';
    private string $classTail = '';
    private int $linesCount = 0;

    /**
     * @param  string                 $path
     * @throws FileNotExistsException
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new FileNotExistsException(self::EXC_MESSAGE);
        }

        $this->initMain($path);

        if ($this->isAtypicalClass()) {
            $this->main = $this->methods = $this->lines = [];
        }

        $this->initClassDetails();
        $this->initConstProps();
    }

    /**
     * Number of lines in the file.
     * @return int
     */
    public function getLinesCount(): int
    {
        return $this->linesCount;
    }

    /**
     * Namespace class.
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Cleared class name.
     * @return string
     */
    public function getClassName(): string
    {
        return end($this->classChunks);
    }

    /**
     * Uncleaned class name (including "final", "abstract", "readonly", etc).
     * @return string
     */
    public function getFullClassName(): string
    {
        return $this->classHead;
    }

    /**
     * Full class name: namespace + class name.
     * @return string
     */
    public function getQualifiedName(): string
    {
        return sprintf('\%s\%s', $this->getNamespace(), $this->getClassName());
    }

    /**
     * Class type: class, interface, trait, enum, etc.
     * @return string
     */
    public function getClassType(): string
    {
        return current(\array_slice($this->classChunks, -2));
    }

    /**
     * Import classes via "use";.
     * @return array
     */
    public function getImports(): array
    {
        $callback = function (string $item) {
            if (!str_starts_with($item, 'use ')) {
                return null;
            }

            return str_replace('use ', '', $item);
        };

        return array_values(array_filter(array_map($callback, $this->main)));
    }

    /**
     * What the class extends, inherits.
     * @return string
     */
    public function getExtends(): string
    {
        if (!str_contains($this->classTail, 'extends ')) {
            return '';
        }

        $extends = explode(' extends ', ' '.$this->classTail);
        $extends = end($extends);

        return current(explode(' ', $extends));
    }

    /**
     * What the class implements.
     * @return array
     */
    public function getImplements(): array
    {
        if (!str_contains($this->classTail, 'implements ')) {
            return [];
        }

        $implements = explode(' implements ', ' '.$this->classTail);
        $implements = end($implements);

        $items = array_map(function (string $item) {
            $item = trim($item);

            return current(explode(' ', $item));
        }, explode(',', $implements));

        return array_map('trim', $items);
    }

    /**
     * List of all class constants.
     * @return array
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * List of all class properties.
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * List of all cases for "enum" type.
     * @return array
     */
    public function getEnumCases(): array
    {
        return $this->cases;
    }

    /**
     * Signatures of all class methods.
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Check whether a class is atypical.
     * @return bool
     */
    public function isAtypicalClass(): bool
    {
        $keys = array_keys($this->atypical);
        $values = array_values($this->atypical);
        rsort($values, SORT_NUMERIC);

        return match (true) {
            current($values) > 1,
            0 === \count($keys),
            \count($keys) > 1 => true,
            default => false
        };
    }

    /**
     * Indicates that the class as a whole is abstract.
     * @return bool
     */
    public function isAbstract(): bool
    {
        $sum = (int) $this->isExplicitAbstract() + (int) $this->isImplicitAbstract();

        return (bool) $sum;
    }

    /**
     * Indicates that the class is abstract because it is so specified in its description.
     * @return bool
     */
    public function isExplicitAbstract(): bool
    {
        return str_contains($this->classHead, 'abstract');
    }

    /**
     * Indicates that a class is abstract because it contains abstract methods.
     * @return bool
     */
    public function isImplicitAbstract(): bool
    {
        return $this->hasAbstractMethods();
    }

    /**
     * Indicates that the class is final.
     * @return bool
     */
    public function isFinal(): bool
    {
        return str_contains($this->classHead, 'final');
    }

    /**
     * Indicates that the class is read-only.
     * @return bool
     */
    public function isReadonly(): bool
    {
        return str_contains($this->classHead, 'readonly');
    }

    /**
     * Indicates that the class is "class" type.
     * @return bool
     */
    public function isClass(): bool
    {
        return self::TYPE_CLASS == $this->getClassType();
    }

    /**
     * Indicates that the class is "interface" type.
     * @return bool
     */
    public function isInterface(): bool
    {
        return self::TYPE_INTERFACE == $this->getClassType();
    }

    /**
     * Indicates that the class is "trait" type.
     * @return bool
     */
    public function isTrait(): bool
    {
        return self::TYPE_TRAIT == $this->getClassType();
    }

    /**
     * Indicates that the class is "enum" type.
     * @return bool
     */
    public function isEnum(): bool
    {
        return self::TYPE_ENUM == $this->getClassType();
    }

    /**
     * Indicates whether the class has a constructor.
     * @return bool
     */
    public function hasConstructor(): bool
    {
        return $this->hasSomeMethods(' __construct');
    }

    /**
     * Indicates whether the class has abstract methods.
     * @return bool
     */
    public function hasAbstractMethods(): bool
    {
        return $this->hasSomeMethods('abstract ');
    }

    /**
     * Result as an array.
     * @return array
     */
    public function toArray(): array
    {
        $class = new \ReflectionClass($this);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $result = [];
        $notNeed = ['__construct', 'toArray', '__toString'];

        foreach ($methods as $method) {
            $methodName = $method->getName();

            if (\in_array($methodName, $notNeed, true)) {
                continue;
            }

            $snake = $this->camelToSnake($methodName);
            $result[$snake] = $this->$methodName();
        }

        ksort($result);

        return $result;
    }

    /**
     * Result as a string.
     * @return string
     */
    public function __toString(): string
    {
        $result = '';
        $items = $this->toArray();

        foreach ($items as $key => $value) {
            if (\is_array($value)) {
                $value = \count($value) ? implode(', ', $value) : '[]';
            }

            if (\is_bool($value)) {
                $value = (int) $value;
            }

            $result .= sprintf("%s: %s\n", $key, $value);
        }

        return $result;
    }

    /**
     * Convert "camelCase" to "snake_case".
     * @param  string $string
     * @return string
     */
    private function camelToSnake(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    /**
     * Basic preparation.
     * @param  string $path
     * @return void
     */
    protected function initMain(string $path): void
    {
        $lines = file($path);
        $this->linesCount = \count($lines);
        $atypical = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (0 == \strlen($trimmed)) {
                continue;
            }

            if (preg_match('/[a-zA-Z]/', $line[0])) {
                $trimmed = str_replace([';'], '', $trimmed);
                if (str_starts_with($trimmed, 'namespace ')) {
                    $this->namespace = str_replace('namespace ', '', $trimmed);
                }
                $this->main[] = $trimmed;
            }

            if ($this->isMethod($trimmed)) {
                $this->methods[] = $trimmed;
            }

            if ($this->isClassSign($trimmed)) {
                $atypical[] = trim(substr($trimmed, 0, strpos($trimmed, ' ')));
            }

            $this->lines[] = $trimmed;
        }

        $this->atypical = array_count_values($atypical);
    }

    /**
     * Preparing to work with the class string.
     * @return void
     */
    protected function initClassDetails(): void
    {
        $class = end($this->main);
        $class = trim($class, ' ;');
        $class = str_replace('  ', ' ', $class);
        $sharped = str_replace([' implements ', ' extends '], '#', $class);
        $this->classHead = trim(current(explode('#', $sharped)));
        $this->classTail = trim(str_replace($this->classHead, '', $class));
        $this->classChunks = explode(' ', $this->classHead);
    }

    /**
     * Preparing to work with the constants and properties.
     * @return void
     */
    protected function initConstProps(): void
    {
        $bracket = false;
        foreach ($this->lines as $item) {
            if ('{' == $item) {
                $bracket = true;
            }
            if ($bracket && !str_contains($item, '(')) {
                if (!preg_match('/[a-zA-Z]/', $item[0])) {
                    continue;
                }
                $item = !str_contains($item, ' //')
                    ? $item
                    : trim(substr($item, 0, strpos($item, '/')));

                $item = trim($item, ' ;');
                $item = !str_ends_with($item, '[') ? $item : $item.'...]';

                if (str_contains($item, 'const ')) {
                    $this->constants[] = $item;
                } elseif (str_contains($item, 'case ')) {
                    $this->cases[] = $item;
                } else {
                    $this->properties[] = $item;
                }
            }

            // get constants and properties up to the first function
            if (str_contains($item, 'function ')) {
                break;
            }
        }
    }

    /**
     * Searching for something among methods.
     * @param  string $needle
     * @return bool
     */
    protected function hasSomeMethods(string $needle): bool
    {
        $callback = function (string $item) use ($needle) {
            return str_contains($item, $needle);
        };

        $found = array_filter($this->getMethods(), $callback);

        return (bool) \count($found);
    }

    /**
     * Whether the string is a class sign.
     * @param  string $line
     * @return bool
     */
    protected function isClassSign(string $line): bool
    {
        $line = trim($line);

        return match (true) {
            str_starts_with($line, 'abstract class '),
            str_starts_with($line, 'final class '),
            str_starts_with($line, 'class '),
            str_starts_with($line, 'interface '),
            str_starts_with($line, 'trait '),
            str_starts_with($line, 'enum ') => true,
            default => false
        };
    }

    /**
     * Whether the string is a method signature.
     * @param  string $line
     * @return bool
     */
    protected function isMethod(string $line): bool
    {
        $trimmed = trim($line);
        $hasFunction = str_starts_with($trimmed, 'function ')
            || str_contains($trimmed, ' function ');

        return $hasFunction && \in_array($trimmed[0], ['a', 'p', 'f', 's'], true);
    }
}
