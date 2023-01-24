<?php

namespace BradieTilley\PestPrinter\Exceptions;

use Exception;

class InvalidConfigurationException extends Exception
{
    public static function invalidString(string $key): self
    {
        return new self(sprintf(
            'Invalid configuration value found for %s (must be string)',
            $key
        ));
    }

    public static function invalidBoolean(string $key): self
    {
        return new self(sprintf(
            'Invalid configuration value found for %s (must be boolean)',
            $key
        ));
    }

    public static function invalidInteger(string $key): self
    {
        return new self(sprintf(
            'Invalid configuration value found for %s (must be integer)',
            $key
        ));
    }

    public static function invalidFloat(string $key): self
    {
        return new self(sprintf(
            'Invalid configuration value found for %s (must be float)',
            $key
        ));
    }
}
