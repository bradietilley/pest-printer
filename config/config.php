<?php

return [
    'display' => [
        'delimiter' => [
            'text' => '-',
            'class' => 'text-zinc-700',
        ],

        'datasetIndentation' => [
            'text' => '>>>>',
            'spacing' => 1,
            'class' => 'text-cyan-600',
        ],

        'datasetName' => [
            'class' => 'text-cyan-600',
        ],

        'statusMessage' => [
            'spacing' => 1,
            'text' => '⟶  ',
        ],

        'rowPrefix' => [
            'text' => '↳',
        ],

        'rowSuffix' => [
            'text' => '↲',
        ],

        'testNameElipsis' => [
            'text' => '.',
        ],

        'failedTestDelimiter' => [
            'class' => 'text-gray',
        ],

        'failedTestDelimiter1' => [
            'text' => '•',
        ],

        'failedTestDelimiter2' => [
            'text' => '»',
        ],

        'failedTestDelimiter3' => [
            'text' => '›',
        ],

        'testName' => [
            'class' => 'bg-gray-800 text-white',
        ],

        'exceptionPreview' => [
            'labels' => [
                'class' => 'text-gray-700',
            ],
        ],

        'testIndex' => [
            'class' => 'text-zinc-600',
        ],

        'widths' => [
            'left' => 2,
            'index' => 9,
            'right' => 2,
            'padding' => 1,
            'status' => 2,
            'time' => 7,
        ],

        'color' => [
            'safeMode' => false,
        ],
    ],

    'timing' => [
        'grades' => [
            'fast' => [
                'time' => 0.2,
                'class' => 'text-green-500',
            ],
            'okay' => [
                'time' => 0.5,
                'class' => 'text-amber-500',
            ],
            'slow' => [
                'time' => 31536000,
                'class' => 'text-red-500',
            ],
            'null' => [
                'class' => 'text-gray-500',
            ],
        ],
    ],

    'statuses' => [
        'pending' => [
            'present' => 'Pending',
            'past' => 'Pending',
            'plural' => 'Pendings',
            'icon' => 'P',
            'showMessageInline' => false,
            'color' => 'gray',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
            'showAdditionalInformation' => false,
        ],
        'success' => [
            'present' => 'Pass',
            'past' => 'Passed',
            'plural' => 'Passes',
            'icon' => '✓',
            'showMessageInline' => false,
            'color' => 'green',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
            'showAdditionalInformation' => false,
        ],
        'failed' => [
            'present' => 'Failure',
            'past' => 'Failed',
            'plural' => 'Failures',
            'icon' => '✗',
            'showMessageInline' => false,
            'color' => 'red',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
            'showAdditionalInformation' => true,
        ],
        'error' => [
            'present' => 'Error',
            'past' => 'Errored',
            'plural' => 'Errors',
            'icon' => 'E',
            'showMessageInline' => false,
            'color' => 'red',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
            'showAdditionalInformation' => true,
        ],
        'warning' => [
            'present' => 'Warning',
            'past' => 'Warned',
            'plural' => 'Warnings',
            'icon' => '!',
            'showMessageInline' => true,
            'color' => 'yellow',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
            'showAdditionalInformation' => true,
        ],
        'skipped' => [
            'present' => 'Skip',
            'past' => 'Skipped',
            'plural' => 'Skips',
            'icon' => 'S',
            'showMessageInline' => true,
            'color' => 'yellow',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
            'showAdditionalInformation' => true,
        ],
        'incomplete' => [
            'present' => 'Incomplete',
            'past' => 'Incompleted',
            'plural' => 'Incompleted',
            'icon' => 'I',
            'showMessageInline' => true,
            'color' => 'yellow',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
            'showAdditionalInformation' => true,
        ],
        'risky' => [
            'present' => 'Risky',
            'past' => 'Risky',
            'plural' => 'Risky',
            'icon' => 'R',
            'showMessageInline' => true,
            'color' => 'yellow',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
            'showAdditionalInformation' => true,
        ],
        'unknown' => [
            'present' => 'Unknown',
            'past' => 'Unknown',
            'plural' => 'Unknown',
            'icon' => '?',
            'showMessageInline' => true,
            'color' => 'gray',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
            'showAdditionalInformation' => true,
        ],
    ],
];
