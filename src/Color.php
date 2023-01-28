<?php

namespace BradieTilley\PestPrinter;

class Color
{
    /**
     * Convert the given Termwind CSS class list to a terminal-safe
     * class list
     */
    public static function safe(string $unsafe): string
    {
        $parts = explode(' ', $unsafe);

        foreach ($parts as $key => $part) {
            $regex = '/^(bg|text)-([^-]+)-?(\d+)?$/';
            preg_match($regex, $part, $matches);

            $matches[3] ??= null;
            [, $type, $color, $darkness] = $matches;

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

            $parts[$key] = sprintf('%s-%s', $type, $color);
        }

        return implode(' ', $parts);
    }

    /**
     * Convert the given Termwind CSS class list to a terminal-safe
     * class list, if the config is set to use safe colors.
     */
    public static function safeIfConfigured(string $unsafe): string
    {
        if (! Config::getSafeColorMode()) {
            return $unsafe;
        }

        return self::safe($unsafe);
    }
}
