<?php

namespace BradieTilley;

class PestPrinterConfig
{
    public const COLOR_MODE_DEFAULT = 'default';
    public const COLOR_MODE_SAFE = 'safe';

    /**
     * Get the termwind/tailwind css class name for the given color combination
     * if colorMode is default, otherwise return the safe name
     */
    public static function color(string $name): string
    {
        $regex = '/^(bg|text)-([^-]+)-?(\d+)?$/';
        preg_match($regex, $name, $matches);

        $matches[3] ??= null;
        [$name, $type, $color, $darkness] = $matches;

        if (self::colorMode() === self::COLOR_MODE_SAFE) {
            $map = [
                'amber' => 'yellow',
                'orange' => 'yellow',
                'lime' => 'green',
                'grey' => 'gray',
                'darkgray' => 'gray',
                'lightgray' => 'gray',
                'zinc' => 'gray',
                'slate' => 'gray',
            ];
            
            $color = $map[$color] ?? $color;

            return sprintf('%s-%s', $type, $color);
        }

        return sprintf('%s-%s-%d', $type, $color, $darkness);
    }

    /**
     * @todo use laravel config
     */
    public static function colorMode(): string
    {
        return env('PEST_PRINTER_COLOR_MODE', self::COLOR_MODE_DEFAULT);
    }

    /**
     * @todo use laravel config
     */
    public static function statusColor(string $key, string $default = 'gray'): string
    {
        $colors = [
            'pending' => 'gray',
            'success' => 'green',
            'failed' => 'red',
            'warning' => 'yellow',
            'error' => 'red',
            'skipped' => 'yellow',
            'incomplete' => 'yellow',
            'risky' => 'yellow',
            'unknown' => 'gray',
        ];

        return (string) ($colors[$key] ?? $default);
    }
}