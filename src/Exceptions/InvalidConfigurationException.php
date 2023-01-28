<?php

namespace BradieTilley\PestPrinter\Exceptions;

use Exception;

class InvalidConfigurationException extends Exception
{
    /**
     * Thrown when a configuration value was expected to be a string but was not
     */
    public static function invalidString(string $key, mixed $value): self
    {
        return new self(sprintf(
            'Invalid configuration value `%s`, expected string but found %s.',
            $key,
            self::detectType($value),
        ));
    }

    /**
     * Thrown when a configuration value was expected to be a boolean but was not
     */
    public static function invalidBoolean(string $key, mixed $value): self
    {
        return new self(sprintf(
            'Invalid configuration value `%s`, expected boolean but found %s.',
            $key,
            self::detectType($value),
        ));
    }

    /**
     * Thrown when a configuration value was expected to be an integer but was not
     */
    public static function invalidInteger(string $key, mixed $value): self
    {
        return new self(sprintf(
            'Invalid configuration value `%s`, expected integer but found %s.',
            $key,
            self::detectType($value),
        ));
    }

    /**
     * Thrown when a configuration value was expected to be a float but was not
     */
    public static function invalidFloat(string $key, mixed $value): self
    {
        return new self(sprintf(
            'Invalid configuration value `%s`, expected float but found %s.',
            $key,
            self::detectType($value),
        ));
    }

    /**
     * Determine the type of variable
     */
    private static function detectType(mixed $value): string
    {
        return match (true) {
            is_string($value) => 'string',
            is_float($value) => 'float',
            is_integer($value) => 'integer',
            is_array($value) => 'array',
            is_object($value) => 'object',
            is_resource($value) => 'resource',
            default => 'unknown',
        };
    }
}
