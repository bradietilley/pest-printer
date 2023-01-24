<?php

namespace BradieTilley\PestPrinter;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config as Setting;
use Throwable;

class Config
{
    public const CONFIG_KEY = 'printer';

    private static ?array $config = null;

    public static function flush(): void
    {
        static::$config = null;
    }

    public static function read(): void
    {
        try {
            /**
             * Try read from Laravel's Config facade, however there's an
             * issue when this is run after tearDown when a test is sent
             * to the printer where `Target class [config] does not exist`
             *
             * To bypass this, we'll offer Laravel a chance and fallback
             * to the good ol' `require` approach. SSDD right?
             */
            static::$config = Setting::get(self::CONFIG_KEY, []);
        } catch (Throwable $error) {
            static::$config = require __DIR__.'/../config/config.php';
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        // Read the configuration once
        if (static::$config === null) {
            static::read();
        }

        return Arr::get(static::$config, $key, $default);
    }

    public static function getDelimiterText(): string
    {
        return self::get('display.delimiter.text', '-');
    }

    public static function getDelimiterClass(): string
    {
        return self::get('display.delimiter.class', 'text-zinc-700');
    }

    public static function getDatasetIndentText(): string
    {
        return self::get('display.datasetIndentation.text', '>>>>');
    }

    public static function getDatasetIndentSpacing(): int
    {
        return self::get('display.datasetIndentation.spacing', 1);
    }

    public static function getDatasetIndentClass(): string
    {
        return self::get('display.datasetIndentation.class', '');
    }

    public static function getDatasetNameClass(): string
    {
        return self::get('display.datasetName.class', '');
    }

    public static function getStatusMessageSpacing(): int
    {
        return self::get('display.statusMessage.spacing', 1);
    }

    public static function getStatusMessageText(): string
    {
        return self::get('display.statusMessage.text', '⟶  ');
    }

    public static function getRowPrefixText(): string
    {
        return self::get('display.rowPrefix.text', '↳');
    }

    public static function getRowSuffixText(): string
    {
        return self::get('display.rowSuffix.text', '↲');
    }

    public static function getTestNameElipsisText(): string
    {
        return self::get('display.testNameElipsis.text', '.');
    }

    public static function getFailedTestDelimiter1Text(): string
    {
        return self::get('display.failedTestDelimiter1.text', '•');
    }

    public static function getFailedTestDelimiter2Text(): string
    {
        return self::get('display.failedTestDelimiter2.text', '»');
    }

    public static function getFailedTestDelimiter3Text(): string
    {
        return self::get('display.failedTestDelimiter3.text', '›');
    }

    public static function getFailedTestDelimiterClass(): string
    {
        return self::get('display.failedTestDelimiter.class', 'text-gray');
    }
}
