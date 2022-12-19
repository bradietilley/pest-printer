<?php

namespace BradieTilley;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Termwind\Termwind;

use function Termwind\{render};

abstract class Renderer
{
    protected static ?OutputInterface $output = null;

    /**
     * Print the given text as-is
     */
    public static function raw(string $text): void
    {
        self::getOutput()->writeLn($text);
    }

    /**
     * Get the output interface (console output by default)
     */
    public static function getOutput(): OutputInterface
    {
        return static::$output ??= new ConsoleOutput();
    }

    /**
     * Override the output interface used by Termwind (and this package)
     */
    public static function setOutput(OutputInterface $output): void
    {
        static::$output = $output;
    }
    
    /**
     * Render the given HTML via Termwind
     */
    public static function render(string $html): void
    {
        Termwind::renderUsing(self::getOutput());

        render($html);
    }
}