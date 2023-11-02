<?php

/*
 * This file is part of ClassExplorer package.
 *
 * (c) Pavel Vasin <phacman@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Formatter;

/**
 * Formatter interface for console output.
 */
interface OutputFormatterInterface
{
    /**
     * Sets the decorated flag.
     *
     * @param  bool $decorated
     * @return void
     */
    public function setDecorated(bool $decorated);

    /**
     * Whether the output will decorate messages.
     */
    public function isDecorated(): bool;

    /**
     * Sets a new style.
     *
     * @param  string                        $name
     * @param  OutputFormatterStyleInterface $style
     * @return void
     */
    public function setStyle(string $name, OutputFormatterStyleInterface $style);

    /**
     * Checks if output formatter has style with specified name.
     * @param string $name
     */
    public function hasStyle(string $name): bool;

    /**
     * Gets style options from style with specified name.
     *
     * @param  string                    $name
     * @throws \InvalidArgumentException When style isn't defined
     */
    public function getStyle(string $name): OutputFormatterStyleInterface;

    /**
     * Formats a message according to the given styles.
     * @param ?string $message
     */
    public function format(?string $message): ?string;
}
