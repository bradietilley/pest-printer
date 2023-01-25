<?php

return [
    'display' => [
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
        'delimiter' => [
            'text' => '-',
            'class' => 'text-zinc-700',
        ],

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
        'datasetIndentation' => [
            'text' => '>>>>',
            'spacing' => 1,
            'class' => 'text-cyan-600',
        ],

        /**
         * The dataset name colouring is applied for the dataset name only.
         *
         * E.g:
         *
         * it can do something great
         * >>>> [CLASS]as an admin[/CLASS]
         * >>>> [CLASS]as a customer[/CLASS]
         */
        'datasetName' => [
            'class' => 'text-cyan-600',
        ],

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
        'statusMessage' => [
            'spacing' => 1,
            'text' => '⟶  ',
        ],

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
        'rowPrefix' => [
            'text' => '↳',
        ],

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
        'rowSuffix' => [
            'text' => '↲',
        ],

        /**
         * The elipsis text is repeated to fill in the gap between the end of
         * the test name (or dataset names) and the time it took to run the test.
         *
         *
         * E.g.
         *
         *     ✗  it can do something great [ELIPSIS] 0.005s
         */
        'testNameElipsis' => [
            'text' => '.',
            'class' => 'class',
        ],

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
        'failedTestDelimiter' => [
            'class' => 'text-gray',
        ],

        /**
         * The text for the first delimiter that separates the segments of the
         * test identifier, displayed at the end of when printing out the reason
         * a test has failed.
         *
         *         Namespace     Test Name              Dataset Name
         *  [TEXT] Unit\MyTest » you can do something › as an admin
         */
        'failedTestDelimiter1' => [
            'text' => '•',
        ],

        /**
         * The text for the second delimiter that separates the segments of the
         * test identifier, displayed at the end of when printing out the reason
         * a test has failed.
         *
         *    Namespace          Test Name              Dataset Name
         *  • Unit\MyTest [TEXT] you can do something › as an admin
         */
        'failedTestDelimiter2' => [
            'text' => '»',
        ],

        /**
         * The text for the first delimiter that separates the segments of the
         * test identifier, displayed at the end of when printing out the reason
         * a test has failed.
         *
         *    Namespace     Test Name                   Dataset Name
         *  • Unit\MyTest » you can do something [TEXT] as an admin
         */
        'failedTestDelimiter3' => [
            'text' => '›',
        ],

        /**
         * The test name is printed as the testing begins on a new test file.
         *
         * E.g.
         *
         *    [CLASS]Unit\ExampleTest[/CLASS]
         */
        'testName' => [
            'class' => 'bg-gray-800 text-white',
        ],

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
        'exceptionPreview' => [
            'labels' => [
                'class' => 'text-gray-700',
            ],
        ],

        /**
         * The test index shows the current position and total number of tests
         * in a given test case.
         *
         * E.g:
         *
         *     [COLOR][1/2][/COLOR] ✓ it can do something great
         *     [COLOR][2/2][/COLOR] ✓ it can do something awesome
         */
        'testIndex' => [
            'class' => 'text-zinc-600',
        ],

        /**
         * Each "column" in the printer may be capped to a given width, except for
         * the test/dataset name column which fills in the remainder gap. Note:
         * 1 width = 1 character
         *
         * E.g:
         *
         *      [LEFT][INDEX][PADDING][STATUS][PADDING](test name here)[PADDING][TIME][RIGHT]
         */
        'widths' => [
            'left' => 2,
            'index' => 9,
            'right' => 2,
            'padding' => 1,
            'status' => 2,
            'time' => 7,
        ],

        /**
         * WIP: Converts CSS classes for colours to ones that are supported by most terminals
         */
        'color' => [
            'safeMode' => false,
        ],
    ],

    /**
     * Each test case is individually timed by phpunit. This printer allows
     * for you to colourise the times based on 3 different grades: fast, okay
     * and slow. Each grade has a time, whereby if the test case finishes in
     * less time than specified, it inherits the corresponding color of said
     * grade. Currently you CANNOT add more grades. The `null` grade is a
     * fallback for when no time is determined for whatever reason.
     */
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

    /**
     * Each test will finish with a status, which means the test either passed,
     * failed, had a warning, was skipped, is incomplete, was risky, or is
     * unknown to the printer. Here you may chose the language terminology used,
     * icon displayed, color, primary class, inverse class, and whether the
     * status should show the `statusMessage` (from phpunit) inline after the
     * test name, and whether it should show additional information, for example
     * a breakdown of the exception it came across.
     */
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
