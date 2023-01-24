<?php

namespace BradieTilley\PestPrinter;

use BradieTilley\PestPrinter\Exceptions\InvalidConfigurationException;
use BradieTilley\PestPrinter\Objects\Status;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config as Setting;
use Throwable;

class Config
{
    public const CONFIG_KEY = 'printer';

    private static ?array $config = null;

    public static function flush(): void
    {
        self::$config = null;
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
            self::$config = (array) Setting::get(self::CONFIG_KEY, []);
        } catch (Throwable $error) {
            self::$config = (array) require __DIR__.'/../config/config.php';
        }
    }

    public static function all(): array
    {
        // Read the configuration once
        if (self::$config === null) {
            static::read();
        }

        return (array) self::$config;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        // Read the configuration once
        if (self::$config === null) {
            static::read();
        }

        return Arr::get(self::all(), $key, $default);
    }

    public static function getDelimiterText(): string
    {
        return self::getString('display.delimiter.text', '-');
    }

    public static function getDelimiterClass(): string
    {
        return self::getString('display.delimiter.class', 'text-zinc-700');
    }

    public static function getDatasetIndentText(): string
    {
        return self::getString('display.datasetIndentation.text', '>>>>');
    }

    public static function getDatasetIndentSpacing(): int
    {
        return self::getInteger('display.datasetIndentation.spacing', 1);
    }

    public static function getDatasetIndentClass(): string
    {
        return self::getString('display.datasetIndentation.class', '');
    }

    public static function getDatasetNameClass(): string
    {
        return self::getString('display.datasetName.class', '');
    }

    public static function getStatusMessageSpacing(): int
    {
        return self::getInteger('display.statusMessage.spacing', 1);
    }

    public static function getStatusMessageText(): string
    {
        return self::getString('display.statusMessage.text', '⟶  ');
    }

    public static function getRowPrefixText(): string
    {
        return self::getString('display.rowPrefix.text', '↳');
    }

    public static function getRowSuffixText(): string
    {
        return self::getString('display.rowSuffix.text', '↲');
    }

    public static function getRowSuffixClass(): string
    {
        return self::getString('display.rowSuffix.class', 'text-gray-600');
    }

    public static function getTestIndexClass(): string
    {
        return self::getString('display.testIndex.class', 'text-zinc-600');
    }

    public static function getTestNameClass(): string
    {
        return self::getString('display.testName.class', 'bg-gray-700 text-white');
    }

    public static function getTestNameElipsisText(): string
    {
        return self::getString('display.testNameElipsis.text', '.');
    }

    public static function getTestNameElipsisClass(): string
    {
        return self::getString('display.testNameElipsis.class', 'text-gray-600');
    }

    public static function getFailedTestDelimiter1Text(): string
    {
        return self::getString('display.failedTestDelimiter1.text', '•');
    }

    public static function getFailedTestDelimiter2Text(): string
    {
        return self::getString('display.failedTestDelimiter2.text', '»');
    }

    public static function getFailedTestDelimiter3Text(): string
    {
        return self::getString('display.failedTestDelimiter3.text', '›');
    }

    public static function getFailedTestDelimiterClass(): string
    {
        return self::getString('display.failedTestDelimiter.class', 'text-gray');
    }

    public static function getExceptionPreviewLabelClass(): string
    {
        return self::getString('display.exceptionPreview.labels.class', 'text-gray-500');
    }

    /**
     * @param  Status  $status
     * @param  string  $key
     * @return string
     */
    private static function statusKey(Status $status, string $key): string
    {
        return sprintf('statuses.%s.%s', $status->value, $key);
    }

    public static function getStatusIcon(Status $status): string
    {
        return self::getString(self::statusKey($status, 'icon'));
    }

    public static function getStatusTextPastTense(Status $status): string
    {
        return self::getString(self::statusKey($status, 'past'));
    }

    public static function getStatusTextPresentTense(Status $status): string
    {
        return self::getString(self::statusKey($status, 'present'));
    }

    public static function getStatusTextPluralTerm(Status $status): string
    {
        return self::getString(self::statusKey($status, 'plural'));
    }

    public static function getStatusShowMessageInline(Status $status): bool
    {
        return self::getBoolean(self::statusKey($status, 'showMessageInline'));
    }

    public static function getStatusColor(Status $status): string
    {
        return self::getString(self::statusKey($status, 'color'));
    }

    public static function getStatusPrimaryCss(Status $status): string
    {
        return str_replace(
            ':color',
            self::getStatusColor($status),
            self::getString(self::statusKey($status, 'primaryCss')),
        );
    }

    public static function getStatusInverseCss(Status $status): string
    {
        return str_replace(
            ':color',
            self::getStatusColor($status),
            self::getString(self::statusKey($status, 'inverseCss')),
        );
    }

    public static function getStatusShowAdditionalInformation(Status $status): bool
    {
        return self::getBoolean(self::statusKey($status, 'showAdditionalInformation'));
    }

    public static function getWidthLeft(): int
    {
        return self::getInteger('display.widths.left', 2);
    }

    public static function getWidthIndex(): int
    {
        return self::getInteger('display.widths.index', 9);
    }

    public static function getWidthRight(): int
    {
        return self::getInteger('display.widths.right', 2);
    }

    public static function getWidthPadding(): int
    {
        return self::getInteger('display.widths.padding', 1);
    }

    public static function getWidthStatus(): int
    {
        return self::getInteger('display.widths.status', 2);
    }

    public static function getWidthTime(): int
    {
        return self::getInteger('display.widths.time', 7);
    }

    private static function getString(string $key, string $default = ''): string
    {
        $value = self::get($key, $default);

        if (! is_string($value)) {
            throw InvalidConfigurationException::invalidString($key);
        }

        return $value;
    }

    private static function getBoolean(string $key, bool $default = false): bool
    {
        $value = self::get($key, $default);

        if (! is_bool($value)) {
            throw InvalidConfigurationException::invalidBoolean($key);
        }

        return $value;
    }

    private static function getInteger(string $key, int $default = 0): int
    {
        $value = self::get($key, $default);

        if (! is_int($value)) {
            throw InvalidConfigurationException::invalidInteger($key);
        }

        return $value;
    }
}
