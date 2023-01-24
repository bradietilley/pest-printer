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
        ]
    ],

    'statuses' => [
        'pending' => [
            'present' => 'Pending',
            'past' => 'Pending',
            'icon' => 'P',
            'showMessageInline' => false,
            'color' => 'gray',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
        ],
        'success' => [
            'present' => 'Pass',
            'past' => 'Passed',
            'icon' => '✓',
            'showMessageInline' => false,
            'color' => 'green',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
        ],
        'failed' => [
            'present' => 'Failure',
            'past' => 'Failed',
            'icon' => '✗',
            'showMessageInline' => false,
            'color' => 'red',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
        ],
        'error' => [
            'present' => 'Error',
            'past' => 'Errored',
            'icon' => 'E',
            'showMessageInline' => false,
            'color' => 'red',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
        ],
        'warning' => [
            'present' => 'Warning',
            'past' => 'Warned',
            'icon' => '!',
            'showMessageInline' => true,
            'color' => 'yellow',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
        ],
        'skipped' => [
            'present' => 'Skip',
            'past' => 'Skipped',
            'icon' => 'S',
            'showMessageInline' => true,
            'color' => 'yellow',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
        ],
        'incomplete' => [
            'present' => 'Incomplete',
            'past' => 'Incompleted',
            'icon' => 'I',
            'showMessageInline' => true,
            'color' => 'yellow',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
        ],
        'risky' => [
            'present' => 'Risky',
            'past' => 'Risky',
            'icon' => 'R',
            'showMessageInline' => true,
            'color' => 'yellow',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
        ],
        'unknown' => [
            'present' => 'Unknown',
            'past' => 'Unknown',
            'icon' => '?',
            'showMessageInline' => true,
            'color' => 'gray',
            'primaryCss' => 'text-:color',
            'inverseCss' => 'bg-:color-700 text-white',
        ],
    ],
];