<?php

namespace BradieTilley\PestPrinter;

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

    public static function all(): array
    {
        // Read the configuration once
        if (static::$config === null) {
            static::read();
        }

        return static::$config;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        // Read the configuration once
        if (static::$config === null) {
            static::read();
        }

        return Arr::get(static::$config, $key, $default);
    }

    /**
     * @return string
     */
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

    public static function getRowSuffixClass(): string
    {
        return self::get('display.rowSuffix.class', 'text-gray-600');
    }

    public static function getTestIndexClass(): string
    {
        return self::get('display.testIndex.class', 'text-zinc-600');
    }

    public static function getTestNameClass(): string
    {
        return self::get('display.testName.class', 'bg-gray-700 text-white');
    }

    public static function getTestNameElipsisText(): string
    {
        return self::get('display.testNameElipsis.text', '.');
    }

    public static function getTestNameElipsisClass(): string
    {
        return self::get('display.testNameElipsis.class', 'text-gray-600');
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

    public static function getExceptionPreviewLabelClass(): string
    {
        return self::Get('display.exceptionPreview.labels.class', 'text-gray-500');
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
        return self::get(self::statusKey($status, 'icon'));
    }

    public static function getStatusTextPastTense(Status $status): string
    {
        return self::get(self::statusKey($status, 'past'));
    }

    public static function getStatusTextPresentTense(Status $status): string
    {
        return self::get(self::statusKey($status, 'present'));
    }

    public static function getStatusTextPluralTerm(Status $status): string
    {
        return self::get(self::statusKey($status, 'plural'));
    }

    public static function getStatusShowMessageInline(Status $status): bool
    {
        return self::get(self::statusKey($status, 'showMessageInline'));
    }

    public static function getStatusColor(Status $status): string
    {
        return self::get(self::statusKey($status, 'color'));
    }

    public static function getStatusPrimaryCss(Status $status): string
    {
        return str_replace(
            ':color',
            self::getStatusColor($status),
            self::get(self::statusKey($status, 'primaryCss')),
        );
    }

    public static function getStatusInverseCss(Status $status): string
    {
        return str_replace(
            ':color',
            self::getStatusColor($status),
            self::get(self::statusKey($status, 'inverseCss')),
        );
    }

    public static function getStatusShowAdditionalInformation(Status $status): bool
    {
        return self::get(self::statusKey($status, 'showAdditionalInformation'));
    }

    public static function getWidthLeft(): int
    {
        return self::get('display.widths.left', 2);
    }

    public static function getWidthIndex(): int
    {
        return self::get('display.widths.index', 9);
    }

    public static function getWidthRight(): int
    {
        return self::get('display.widths.right', 2);
    }

    public static function getWidthPadding(): int
    {
        return self::get('display.widths.padding', 1);
    }

    public static function getWidthStatus(): int
    {
        return self::get('display.widths.status', 2);
    }

    public static function getWidthTime(): int
    {
        return self::get('display.widths.time', 7);
    }
}
