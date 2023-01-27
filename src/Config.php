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

        $config = self::all();

        return Arr::get($config, $key, $default);
    }

    /**
     * The delimiter is displayed around each failure breakdown
     *
     * E.g:
     *
     * [DELIMITER]
     * Failure #1
     * Failed to assert Blah
     * Stack trace here
     * [DELIMITER]
     * Failure #2
     * Failed to assert Blah
     * Stack trace here
     * [DELIMITER]
     */
    public static function getDelimiterText(): string
    {
        return self::getString('display.delimiter.text', '-');
    }

    /**
     * The delimiter is displayed around each failure breakdown
     *
     * E.g:
     *
     * [DELIMITER]
     * Failure #1
     * Failed to assert Blah
     * Stack trace here
     * [DELIMITER]
     * Failure #2
     * Failed to assert Blah
     * Stack trace here
     * [DELIMITER]
     */
    public static function getDelimiterClass(): string
    {
        return self::getClassString('display.delimiter.class', 'text-zinc-700');
    }

    /**
     * The dataset indentation is used whenever your tests utilise
     * dataset, and is applied to each dataset's name.
     *
     * E.g:
     *
     * it can do something great
     * [INDENTATION] as an admin
     * [INDENTATION] as a customer
     */
    public static function getDatasetIndentText(): string
    {
        return self::getString('display.datasetIndentation.text', '>>>>');
    }

    /**
     * The dataset indentation is used whenever your tests utilise
     * dataset, and is applied to each dataset's name.
     *
     * E.g:
     *
     * it can do something great
     * [INDENTATION] as an admin
     * [INDENTATION] as a customer
     */
    public static function getDatasetIndentSpacing(): int
    {
        return self::getInteger('display.datasetIndentation.spacing', 1);
    }

    /**
     * The dataset indentation is used whenever your tests utilise
     * dataset, and is applied to each dataset's name.
     *
     * E.g:
     *
     * it can do something great
     * [INDENTATION] as an admin
     * [INDENTATION] as a customer
     */
    public static function getDatasetIndentClass(): string
    {
        return self::getClassString('display.datasetIndentation.class', '');
    }

    /**
     * The dataset name colouring is applied for the dataset name only.
     *
     * E.g:
     *
     * it can do something great
     * >>>> [CLASS]as an admin[/CLASS]
     * >>>> [CLASS]as a customer[/CLASS]
     */
    public static function getDatasetNameClass(): string
    {
        return self::getClassString('display.datasetName.class', '');
    }

    /**
     * The status message is shown for statuses that have a corresponding
     * "statusMessage" (see phpunit). The `showMessageInline` option for
     * each status (config `printer.statuses.*.showMessageInline`) allows
     * you to control which statuses will show this message.
     *
     * E.g. a skipped (S) test:
     *
     *     S  it can do something great
     *        [TEXT][SPACING] This test is not working so we are skipping it
     */
    public static function getStatusMessageSpacing(): int
    {
        return self::getInteger('display.statusMessage.spacing', 1);
    }

    /**
     * The status message is shown for statuses that have a corresponding
     * "statusMessage" (see phpunit). The `showMessageInline` option for
     * each status (config `printer.statuses.*.showMessageInline`) allows
     * you to control which statuses will show this message.
     *
     * E.g. a skipped (S) test:
     *
     *     S  it can do something great
     *        [TEXT][SPACING] This test is not working so we are skipping it
     */
    public static function getStatusMessageText(): string
    {
        return self::getString('display.statusMessage.text', '⟶  ');
    }

    /**
     * The row prefix is applied whenever a test exceeds one line of text.
     * This occurs when the name of the test is so long that it wraps, and
     * also occurs when the test uses one or more dataset values. This text
     * starts inline with the status icon.
     *
     * E.g.
     *
     *     ✗  it can do something great
     *     [TEXT] >>> dataset name here
     */
    public static function getRowPrefixText(): string
    {
        return self::getString('display.rowPrefix.text', '↳');
    }

    /**
     * The row prefix is applied whenever a test exceeds one line of text.
     * This occurs when the name of the test is so long that it wraps, and
     * also occurs when the test uses one or more dataset values. This text
     * starts inline with the status icon.
     *
     * E.g.
     *
     *     ✗  it can do something great
     *     [TEXT] >>> dataset name here
     */
    public static function getRowSuffixText(): string
    {
        return self::getString('display.rowSuffix.text', '↲');
    }

    /**
     * The row suffix is applied whenever a test exceeds one line of text.
     * This occurs when the name of the test is so long that it wraps, and
     * also occurs when the test uses one or more dataset values. This text
     * starts inline with the recorded time it took to run the test
     *
     * E.g.
     *
     *     ✗  it can do something great ...... 0.005s
     *        and this is a long name ........ [TEXT]
     */
    public static function getRowSuffixClass(): string
    {
        return self::getClassString('display.rowSuffix.class', 'text-gray-600');
    }

    /**
     * The test index shows the current position and total number of tests
     * in a given test case.
     *
     * E.g:
     *
     *     [COLOR][1/2][/COLOR] ✓ it can do something great
     *     [COLOR][2/2][/COLOR] ✓ it can do something awesome
     */
    public static function getTestIndexClass(): string
    {
        return self::getClassString('display.testIndex.class', 'text-zinc-600');
    }

    /**
     * The test name is printed as the testing begins on a new test file.
     *
     * E.g.
     *
     *    [CLASS]Unit\ExampleTest[/CLASS]
     */
    public static function getTestNameClass(): string
    {
        return self::getClassString('display.testName.class', 'bg-gray-700 text-white');
    }

    /**
     * The elipsis text is repeated to fill in the gap between the end of
     * the test name (or dataset names) and the time it took to run the test.
     *
     *
     * E.g.
     *
     *     ✗  it can do something great [ELIPSIS] 0.005s
     */
    public static function getTestNameElipsisText(): string
    {
        return self::getString('display.testNameElipsis.text', '.');
    }

    /**
     * The elipsis text is repeated to fill in the gap between the end of
     * the test name (or dataset names) and the time it took to run the test.
     *
     *
     * E.g.
     *
     *     ✗  it can do something great [ELIPSIS] 0.005s
     */
    public static function getTestNameElipsisClass(): string
    {
        return self::getClassString('display.testNameElipsis.class', 'text-gray-600');
    }

    /**
     * This delimiter separates the 2 or 3 segments of the test identifier
     * and this allows you to change the color of the icons that separate
     * them.
     *
     * E.g.
     *
     *                   Namespace                    Test Name                             Dataset Name
     *  [COLOR]•[/COLOR] Unit\MyTest [COLOR]»[/COLOR] you can do something [COLOR]›[/COLOR] as an admin
     */
    public static function getFailedTestDelimiterClass(): string
    {
        return self::getClassString('display.failedTestDelimiter.class', 'text-gray');
    }

    /**
     * The text for the first delimiter that separates the segments of the
     * test identifier, displayed at the end of when printing out the reason
     * a test has failed.
     *
     *         Namespace     Test Name              Dataset Name
     *  [TEXT] Unit\MyTest » you can do something › as an admin
     */
    public static function getFailedTestDelimiter1Text(): string
    {
        return self::getString('display.failedTestDelimiter1.text', '•');
    }

    /**
     * The text for the second delimiter that separates the segments of the
     * test identifier, displayed at the end of when printing out the reason
     * a test has failed.
     *
     *    Namespace          Test Name              Dataset Name
     *  • Unit\MyTest [TEXT] you can do something › as an admin
     */
    public static function getFailedTestDelimiter2Text(): string
    {
        return self::getString('display.failedTestDelimiter2.text', '»');
    }

    /**
     * The text for the first delimiter that separates the segments of the
     * test identifier, displayed at the end of when printing out the reason
     * a test has failed.
     *
     *    Namespace     Test Name                   Dataset Name
     *  • Unit\MyTest » you can do something [TEXT] as an admin
     */
    public static function getFailedTestDelimiter3Text(): string
    {
        return self::getString('display.failedTestDelimiter3.text', '›');
    }

    /**
     * The exception preview is a block of text that is displayed when
     * detailing why a test has failed through means of printing out the
     * relevant exception. It includes a breakdown of the File and Line
     * which this can control the color of:
     *
     * E.g:
     *
     *        [COLOR]File:[/COLOR]  /path/to/tests/Unit/ExamplePHPTest.php
     *        [COLOR]Line:[/COLOR]  76
     */
    public static function getExceptionPreviewLabelClass(): string
    {
        return self::getClassString('display.exceptionPreview.labels.class', 'text-gray-500');
    }

    /**
     * Internal function to get the given config key for the given status
     * and key
     *
     * E.g:
     *
     * @param  Status  $status  // e.g. SKIPPED
     * @param  string  $key     // e.g. "icon"
     * @return string         // e.g. "statuses.skipped.icon"
     */
    private static function statusKey(Status $status, string $key): string
    {
        return sprintf('statuses.%s.%s', $status->value, $key);
    }

    /**
     * The status icon is displayed between the Index column and Test Name
     * column and helps visually identify a test's status.
     */
    public static function getStatusIcon(Status $status): string
    {
        return self::getString(self::statusKey($status, 'icon'));
    }

    /**
     * The status has a past-tense term
     */
    public static function getStatusTextPastTense(Status $status): string
    {
        return self::getString(self::statusKey($status, 'past'));
    }

    /**
     * The status has a present-tense term
     */
    public static function getStatusTextPresentTense(Status $status): string
    {
        return self::getString(self::statusKey($status, 'present'));
    }

    /**
     * The status has a pluralised term
     */
    public static function getStatusTextPluralTerm(Status $status): string
    {
        return self::getString(self::statusKey($status, 'plural'));
    }

    /**
     * The status may or may not show messages inline
     */
    public static function getStatusShowMessageInline(Status $status): bool
    {
        return self::getBoolean(self::statusKey($status, 'showMessageInline'));
    }

    /**
     * The status has a color which by default is applied to the primary
     * and inverse classes to colourise the icon and other status labels
     */
    public static function getStatusColor(Status $status): string
    {
        return self::getString(self::statusKey($status, 'color'));
    }

    /**
     * Get the given status's primary CSS class list
     */
    public static function getStatusPrimaryCss(Status $status): string
    {
        return str_replace(
            ':color',
            self::getStatusColor($status),
            self::getString(self::statusKey($status, 'primaryCss')),
        );
    }

    /**
     * Get the given status's inverse CSS class list
     */
    public static function getStatusInverseCss(Status $status): string
    {
        return str_replace(
            ':color',
            self::getStatusColor($status),
            self::getString(self::statusKey($status, 'inverseCss')),
        );
    }

    /**
     * Determine whether or not the given status should show additional
     * information, i.e. exceptions caught during the test.
     */
    public static function getStatusShowAdditionalInformation(Status $status): bool
    {
        return self::getBoolean(self::statusKey($status, 'showAdditionalInformation'));
    }

    /**
     * The left "column" in the printer may be capped to a given width.
     *
     * E.g:
     *
     *      [LEFT][INDEX][PADDING][STATUS][PADDING](test name here)[PADDING][TIME][RIGHT]
     */
    public static function getWidthLeft(): int
    {
        return self::getInteger('display.widths.left', 2);
    }

    /**
     * The index "column" in the printer may be capped to a given width.
     *
     * E.g:
     *
     *      [LEFT][INDEX][PADDING][STATUS][PADDING](test name here)[PADDING][TIME][RIGHT]
     */
    public static function getWidthIndex(): int
    {
        return self::getInteger('display.widths.index', 9);
    }

    /**
     * The right "column" in the printer may be capped to a given width.
     *
     * E.g:
     *
     *      [LEFT][INDEX][PADDING][STATUS][PADDING](test name here)[PADDING][TIME][RIGHT]
     */
    public static function getWidthRight(): int
    {
        return self::getInteger('display.widths.right', 2);
    }

    /**
     * The padding around status and test name "columns" in the printer may be capped to a given width.
     *
     * E.g:
     *
     *      [LEFT][INDEX][PADDING][STATUS][PADDING](test name here)[PADDING][TIME][RIGHT]
     */
    public static function getWidthPadding(): int
    {
        return self::getInteger('display.widths.padding', 1);
    }

    /**
     * The status "column" in the printer may be capped to a given width.
     *
     * E.g:
     *
     *      [LEFT][INDEX][PADDING][STATUS][PADDING](test name here)[PADDING][TIME][RIGHT]
     */
    public static function getWidthStatus(): int
    {
        return self::getInteger('display.widths.status', 2);
    }

    /**
     * The time "column" in the printer may be capped to a given width.
     *
     * E.g:
     *
     *      [LEFT][INDEX][PADDING][STATUS][PADDING](test name here)[PADDING][TIME][RIGHT]
     */
    public static function getWidthTime(): int
    {
        return self::getInteger('display.widths.time', 7);
    }

    /**
     * Should the printer convert unsafe Termwind CSS classes (e.g. "bg-amber-400") to a
     * safe Termwind CSS class (e.g. "bg-yellow")
     */
    public static function getSafeColorMode(): bool
    {
        return self::getBoolean('display.color.safeMode', false);
    }

    /**
     * Get the maximum time (in seconds) that a test may run in order to classify
     * as being "fast" grade.
     */
    public static function getTimeGradeFastTime(): float
    {
        return self::getFloat('timing.grades.fast.time', 0.2);
    }

    /**
     * Get the Termwind CSS class list that is applied to tests that are "fast" grade.
     */
    public static function getTimeGradeFastClass(): string
    {
        return self::getClassString('timing.grades.fast.class', 'text-green-500');
    }

    /**
     * Get the maximum time (in seconds) that a test may run in order to classify
     * as being "okay" grade.
     */
    public static function getTimeGradeOkayTime(): float
    {
        return self::getFloat('timing.grades.okay.time', 0.5);
    }

    /**
     * Get the Termwind CSS class list that is applied to tests that are "okay" grade.
     */
    public static function getTimeGradeOkayClass(): string
    {
        return self::getClassString('timing.grades.okay.class', 'text-amber-500');
    }

    /**
     * Get the maximum time (in seconds) that a test may run in order to classify
     * as being "slow" grade.
     */
    public static function getTimeGradeSlowTime(): float
    {
        return self::getFloat('timing.grades.slow.time', 31536000);
    }

    /**
     * Get the Termwind CSS class list that is applied to tests that are "slow" grade.
     */
    public static function getTimeGradeSlowClass(): string
    {
        return self::getClassString('timing.grades.slow.class', 'text-red-500');
    }

    /**
     * Get the Termwind CSS class list that is applied to tests that are "null" grade
     * which is the default for when a time cannot be computed.
     */
    public static function getTimeGradeNullClass(): string
    {
        return self::getClassString('timing.grades.null.class', 'text-gray-500');
    }

    /**
     * Internal use: Fetch the configuration value as a string (phpstan)
     *
     * Cast the given string (class list) to a color-safe class list, if
     * configured to do so.
     */
    private static function getClassString(string $key, string $default = ''): string
    {
        $class = self::getString($key, $default);
        $class = Color::safeIfConfigured($class);

        return $class;
    }

    /**
     * Internal use: Fetch the configuration value as a string (phpstan)
     */
    private static function getString(string $key, string $default = ''): string
    {
        $value = self::get($key, $default);

        if (! is_string($value)) {
            throw InvalidConfigurationException::invalidString($key);
        }

        return $value;
    }

    /**
     * Internal use: Fetch the configuration value as a boolean (phpstan)
     */
    private static function getBoolean(string $key, bool $default = false): bool
    {
        $value = self::get($key, $default);

        if (! is_bool($value)) {
            throw InvalidConfigurationException::invalidBoolean($key);
        }

        return $value;
    }

    /**
     * Internal use: Fetch the configuration value as an integer (phpstan)
     */
    private static function getInteger(string $key, int $default = 0): int
    {
        $value = self::get($key, $default);

        if (! is_int($value)) {
            throw InvalidConfigurationException::invalidInteger($key);
        }

        return $value;
    }

    /**
     * Internal use: Fetch the configuration value as a float (phpstan)
     */
    private static function getFloat(string $key, float $default = 0.0): float
    {
        $value = self::get($key, $default);

        if (is_int($value)) {
            $value = (float) $value;
        }

        if (! is_float($value)) {
            throw InvalidConfigurationException::invalidFloat($key);
        }

        return $value;
    }
}
