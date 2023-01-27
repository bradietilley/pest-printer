<?php

namespace BradieTilley\PestPrinter\Exceptions;

use Exception;

class InvalidConfigurationException extends Exception
{
    /**
     * Thrown when a configuration value was expected to be a string but was not
     */
    public static function invalidString(string $key): self
    {
        return new self(sprintf(
            'Invalid configuration value found for %s (must be string)',
            $key
        ));
    }

    /**
     * Thrown when a configuration value was expected to be a boolean but was not
     */
    public static function invalidBoolean(string $key): self
    {
        return new self(sprintf(
            'Invalid configuration value found for %s (must be boolean)',
            $key
        ));
    }

    /**
     * Thrown when a configuration value was expected to be an integer but was not
     */
    public static function invalidInteger(string $key): self
    {
        return new self(sprintf(
            'Invalid configuration value found for %s (must be integer)',
            $key
        ));
    }

    /**
     * Thrown when a configuration value was expected to be a float but was not
     */
    public static function invalidFloat(string $key): self
    {
        return new self(sprintf(
            'Invalid configuration value found for %s (must be float)',
            $key
        ));
    }
}
