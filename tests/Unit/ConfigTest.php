<?php

use BradieTilley\PestPrinter\Config;
use BradieTilley\PestPrinter\Exceptions\InvalidConfigurationException;
use BradieTilley\PestPrinter\Objects\Status;
use Illuminate\Support\Facades\Config as FacadesConfig;

beforeEach(function () {
    Config::flush();
});

test('local config repository can get a value', function () {
    $value = Config::get('statuses.pending.present', 'default');

    expect($value)->toBe('Pending');
});

test('local config repository can set a value', function () {
    Config::set('statuses.pending.present', 'something custom');
    $value = Config::get('statuses.pending.present', 'default');

    expect($value)->toBe('something custom');
});

test('laravel config repository can set a value', function () {
    FacadesConfig::set('printer.statuses.pending.present', 'Some value');

    $value = Config::get('statuses.pending.present', 'default');

    expect($value)->toBe('Some value');
});

test('laravel config repository can get a value', function () {
    Config::set('statuses.pending.present', 'original');

    $value = FacadesConfig::get('printer.statuses.pending.present', 'default');

    expect($value)->toBe('original');
});

test('config will throw InvalidConfigurationException on expected string being array', function () {
    try {
        Config::set('display.statusMessage.text', []);
    } catch (Throwable $e) {
        $this->fail();
    }

    Config::getStatusMessageText();
})->throws(InvalidConfigurationException::class, 'Invalid configuration value `display.statusMessage.text`, expected string but found array.');

test('config will throw InvalidConfigurationException on expected integer being string', function () {
    try {
        Config::set('display.datasetIndentation.spacing', '5');
    } catch (Throwable $e) {
        $this->fail();
    }

    Config::getDatasetIndentSpacing();
})->throws(InvalidConfigurationException::class, 'Invalid configuration value `display.datasetIndentation.spacing`, expected integer but found string.');

test('config will throw InvalidConfigurationException on expected float being string', function () {
    try {
        Config::set('timing.grades.fast.time', '5.5');
    } catch (Throwable $e) {
        $this->fail();
    }

    Config::getTimeGradeFastTime();
})->throws(InvalidConfigurationException::class, 'Invalid configuration value `timing.grades.fast.time`, expected float but found string.');

test('config will throw InvalidConfigurationException on expected boolean being integer', function () {
    try {
        Config::set('display.color.safeMode', 1);
    } catch (Throwable $e) {
        $this->fail();
    }

    Config::getSafeColorMode();
})->throws(InvalidConfigurationException::class, 'Invalid configuration value `display.color.safeMode`, expected boolean but found integer.');

test('config will NOT throw InvalidConfigurationException on expected float being integer', function () {
    try {
        Config::set('timing.grades.fast.time', 5);
        Config::getTimeGradeFastTime();

        expect(true)->toBeTrue();
    } catch (Throwable $e) {
        $this->fail();
    }
});

$testGetters = [
    'getDelimiterText' => [
        'key' => 'display.delimiter.text',
        'data' => [
            '-',
            '=',
        ],
    ],
    'getDelimiterClass' => [
        'key' => 'display.delimiter.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getDatasetIndentText' => [
        'key' => 'display.datasetIndentation.text',
        'data' => [
            '>>>',
            '-->',
        ],
    ],
    'getDatasetIndentSpacing' => [
        'key' => 'display.datasetIndentation.spacing',
        'data' => [
            1,
            2,
        ],
    ],
    'getDatasetIndentClass' => [
        'key' => 'display.datasetIndentation.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getDatasetNameClass' => [
        'key' => 'display.datasetName.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getStatusMessageSpacing' => [
        'key' => 'display.statusMessage.spacing',
        'data' => [
            1,
            2,
        ],
    ],
    'getStatusMessageText' => [
        'key' => 'display.statusMessage.text',
        'data' => [
            '->',
            '=>',
        ],
    ],
    'getRowPrefixText' => [
        'key' => 'display.rowPrefix.text',
        'data' => [
            '↳',
            'L',
        ],
    ],
    'getRowSuffixText' => [
        'key' => 'display.rowSuffix.text',
        'data' => [
            '↲',
            '_|',
        ],
    ],
    'getRowSuffixClass' => [
        'key' => 'display.rowSuffix.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getTestIndexClass' => [
        'key' => 'display.testIndex.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getTestNameClass' => [
        'key' => 'display.testName.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getTestNameElipsisText' => [
        'key' => 'display.testNameElipsis.text',
        'data' => [
            '.',
            '-',
        ],
    ],
    'getTestNameElipsisClass' => [
        'key' => 'display.testNameElipsis.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getFailedTestDelimiterClass' => [
        'key' => 'display.failedTestDelimiter.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getFailedTestDelimiter1Text' => [
        'key' => 'display.failedTestDelimiter1.text',
        'data' => [
            '>',
            '-',
        ],
    ],
    'getFailedTestDelimiter2Text' => [
        'key' => 'display.failedTestDelimiter2.text',
        'data' => [
            '>',
            '-',
        ],
    ],
    'getFailedTestDelimiter3Text' => [
        'key' => 'display.failedTestDelimiter3.text',
        'data' => [
            '>',
            '-',
        ],
    ],
    'getExceptionPreviewLabelClass' => [
        'key' => 'display.exceptionPreview.labels.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getStatusIcon' => [
        'key' => 'statuses.:status.icon',
        'data' => [
            ['X', Status::ERROR->value],
            ['E', Status::ERROR->value],
            ['P', Status::SUCCESS->value],
            ['S', Status::SUCCESS->value],
        ],
    ],
    'getStatusTextPastTense' => [
        'key' => 'statuses.:status.past',
        'data' => [
            ['Errored', Status::ERROR->value],
            ['Failed', Status::ERROR->value],
            ['Succeeded', Status::SUCCESS->value],
            ['Passed', Status::SUCCESS->value],
        ],
    ],
    'getStatusTextPresentTense' => [
        'key' => 'statuses.:status.present',
        'data' => [
            ['Fail', Status::ERROR->value],
            ['Error', Status::ERROR->value],
            ['Success', Status::SUCCESS->value],
            ['Pass', Status::SUCCESS->value],
        ],
    ],
    'getStatusTextPluralTerm' => [
        'key' => 'statuses.:status.plural',
        'data' => [
            ['Errors', Status::ERROR->value],
            ['Fails', Status::ERROR->value],
            ['Successes', Status::SUCCESS->value],
            ['Passes', Status::SUCCESS->value],
        ],
    ],
    'getStatusShowMessageInline' => [
        'key' => 'statuses.:status.showMessageInline',
        'data' => [
            [true, Status::ERROR->value],
            [false, Status::ERROR->value],
            [true, Status::SUCCESS->value],
            [false, Status::SUCCESS->value],
        ],
    ],
    'getStatusColor' => [
        'key' => 'statuses.:status.color',
        'data' => [
            ['red', Status::ERROR->value],
            ['pink', Status::ERROR->value],
            ['green', Status::SUCCESS->value],
            ['lime', Status::SUCCESS->value],
        ],
    ],
    'getStatusPrimaryCss' => [
        'key' => 'statuses.:status.primaryCss',
        'data' => [
            ['text-red', Status::ERROR->value],
            ['text-pink', Status::ERROR->value],
            ['text-green', Status::SUCCESS->value],
            ['text-lime', Status::SUCCESS->value],
        ],
    ],
    'getStatusInverseCss' => [
        'key' => 'statuses.:status.inverseCss',
        'data' => [
            ['bg-red', Status::ERROR->value],
            ['bg-pink', Status::ERROR->value],
            ['bg-green', Status::SUCCESS->value],
            ['bg-lime', Status::SUCCESS->value],
        ],
    ],
    'getStatusShowAdditionalInformation' => [
        'key' => 'statuses.:status.showAdditionalInformation',
        'data' => [
            [true, Status::ERROR->value],
            [false, Status::ERROR->value],
            [true, Status::SUCCESS->value],
            [false, Status::SUCCESS->value],
        ],
    ],
    'getWidthLeft' => [
        'key' => 'display.widths.left',
        'data' => [
            1,
            2,
        ],
    ],
    'getWidthIndex' => [
        'key' => 'display.widths.index',
        'data' => [
            1,
            2,
        ],
    ],
    'getWidthRight' => [
        'key' => 'display.widths.right',
        'data' => [
            1,
            2,
        ],
    ],
    'getWidthPadding' => [
        'key' => 'display.widths.padding',
        'data' => [
            1,
            2,
        ],
    ],
    'getWidthStatus' => [
        'key' => 'display.widths.status',
        'data' => [
            1,
            2,
        ],
    ],
    'getWidthTime' => [
        'key' => 'display.widths.time',
        'data' => [
            1,
            2,
        ],
    ],
    'getSafeColorMode' => [
        'key' => 'display.color.safeMode',
        'data' => [
            true,
            false,
        ],
    ],
    'getTimeGradeFastTime' => [
        'key' => 'timing.grades.fast.time',
        'data' => [
            0.111,
            0.222,
        ],
    ],
    'getTimeGradeFastClass' => [
        'key' => 'timing.grades.fast.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getTimeGradeOkayTime' => [
        'key' => 'timing.grades.okay.time',
        'data' => [
            0.222,
            0.333,
        ],
    ],
    'getTimeGradeOkayClass' => [
        'key' => 'timing.grades.okay.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getTimeGradeSlowTime' => [
        'key' => 'timing.grades.slow.time',
        'data' => [
            0.333,
            0.444,
        ],
    ],
    'getTimeGradeSlowClass' => [
        'key' => 'timing.grades.slow.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
    'getTimeGradeNullClass' => [
        'key' => 'timing.grades.null.class',
        'data' => [
            'text-red',
            'text-bold',
        ],
    ],
];

foreach ($testGetters as $method => $data) {
    $key = $data['key'];

    test("config getter {$method}", function ($value, string $status = null) use ($method, $key) {
        if ($status !== null) {
            $status = Status::from($status);

            $key = str_replace(':status', $status->value, $key);
        }

        Config::set($key, $value);
        $retrieved = null;

        if ($status !== null) {
            $retrieved = Config::{$method}($status);
        } else {
            $retrieved = Config::{$method}();
        }

        expect($retrieved)->toBe($value);
    })->with($data['data']);
}
