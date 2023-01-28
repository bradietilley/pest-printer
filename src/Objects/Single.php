<?php

namespace BradieTilley\PestPrinter\Objects;

use AssertionError;
use BradieTilley\PestPrinter\Config;
use BradieTilley\PestPrinter\Printer;
use BradieTilley\PestPrinter\Renderer;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\IncompleteTestError;
use PHPUnit\Framework\SkippedTestError;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Warning;
use function Termwind\terminal;
use Throwable;

class Single
{
    protected Name $name;

    protected Status $status;

    protected Time $time;

    protected Throwable|null $error = null;

    protected int $suiteIndex = 0;

    protected int $suiteMax = 0;

    public function __construct(public readonly TestCase $test, public readonly Group $group)
    {
        $this->name = Name::make($test->getName());
        $this->status = Status::PENDING;
        $this->time = new Time();
    }

    public static function make(TestCase $test, Group $group): self
    {
        return new self($test, $group);
    }

    public function hasRun(): bool
    {
        return $this->test->getTestResultObject() !== null;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getTime(): Time
    {
        return $this->time;
    }

    public function getName(): string
    {
        return $this->name->getName();
    }

    public function getDataset(): ?string
    {
        return $this->name->getDataset();
    }

    public function setIndex(int $suiteIndex, int $suiteMax): self
    {
        $this->suiteIndex = $suiteIndex;
        $this->suiteMax = $suiteMax;

        return $this;
    }

    public function start(): void
    {
        // dump('Test started: ' . $this->getName() . ' (dataset: ' . ($this->getDataset() ?? 'null') . ')');
    }

    public function end(): void
    {
        $statusCss = $this->status->css();
        $statusIcon = $this->status->icon();

        $timeText = $this->time->format();
        $timeCss = $this->time->css();
        $name = $this->getName();

        $indexText = "[{$this->suiteIndex}/{$this->suiteMax}]";
        $indexCss = Config::getTestIndexClass();
        $elipsisCss = Config::getTestNameElipsisClass();

        $widths = [
            'left' => Config::getWidthLeft(),
            'index' => Config::getWidthIndex(),
            'right' => Config::getWidthRight(),
            'padding' => Config::getWidthPadding(),
            'status' => Config::getWidthStatus(),
            'time' => Config::getWidthTime(),
        ];

        $otherWidth = $widths['left']
            + $widths['index']
            + $widths['right']
            + ($widths['padding'] * 3)
            + $widths['status']
            + $widths['time'];

        $fullWidth = terminal()->width();
        $nameWidth = $fullWidth - $otherWidth;

        $isDatasetHeader = ($this->name->hasDataset() && ($name !== $this->group->getLastTest()));

        /**
         * Get the name of the test (incl dataset) as a multiline
         * array (capped at the expected width of the name column)
         */
        $text = [];
        assert($nameWidth > 0);
        foreach (mb_str_split($name, $nameWidth) as $line) {
            $text[] = <<<HTML
                <div>{$line}</div>
            HTML;
        }

        if ($this->name->hasDataset()) {
            if (! $isDatasetHeader) {
                $text = [];
            }

            $indent = Config::getDatasetIndentText();
            $spacing = Config::getDatasetIndentSpacing();
            $indentCss = Config::getDatasetIndentClass();

            $datasetCss = Config::getDatasetNameClass();

            $restrictedWidth = strlen($indent) + $spacing;

            $nameMinusRestrictedWidth = $nameWidth - $restrictedWidth;

            assert($nameMinusRestrictedWidth > 0);
            $dataset = mb_str_split((string) $this->getDataset(), $nameMinusRestrictedWidth);

            foreach ($dataset as $datasetpart) {
                $text[] = <<<HTML
                    <span class="{$indentCss} mr-{$spacing}">{$indent}</span>
                    <span class="{$datasetCss}">{$datasetpart}</span>
                HTML;
            }
        }

        $statusMessage = htmlspecialchars(trim(trim($this->test->getStatusMessage()), '.'));
        if (strlen($statusMessage) > 0 && $this->status->showStatusMessageInline()) {
            $spacing = Config::getStatusMessageSpacing();
            $indent = Config::getStatusMessageText();

            $indentWidth = mb_strlen($indent);
            $restrictedWidth = $indentWidth + $spacing;
            $maxLines = 4;
            $nameMinusRestrictedWidth = $nameWidth - $restrictedWidth;

            assert($nameMinusRestrictedWidth > 0);
            $statusMessage = mb_str_split($statusMessage, $nameMinusRestrictedWidth);

            if (count($statusMessage) > $maxLines) {
                $statusMessage = array_slice($statusMessage, 0, $maxLines);
                $last = array_key_last($statusMessage);

                $truncate = ' (truncated)';

                $statusMessage[$last] = substr($statusMessage[$last], 0, 0 - strlen($truncate)).$truncate;
            }

            foreach ($statusMessage as $statusmessagepart) {
                $text[] = <<<HTML
                    <span class="{$statusCss} mr-{$spacing} w-{$indentWidth}">{$indent}</span>
                    <span class="{$statusCss} italic">{$statusmessagepart}</span>
                HTML;
            }
        }

        /**
         * Using the number of expected rows from the $text array,
         * push the status icon to the last row
         */
        $status = [
            $statusIcon,
        ];
        $symbol = Config::getRowPrefixText();
        while (count($status) < count($text)) {
            $status[] = <<<HTML
            <div class="{$statusCss}">{$symbol}</div>
            HTML;
        }

        /**
         * Using the number of expected rows from the $text array,
         * push the time text to the last row
         */
        $time = [
            $timeText,
        ];
        $symbol = Config::getRowSuffixText();
        $symbolCss = Config::getRowSuffixClass();
        while (count($time) < count($text)) {
            $time[] = <<<HTML
            <div class="{$symbolCss}">{$symbol}</div>
            HTML;
        }

        /**
         * Using the number of expected rows from the $text array,
         * push the index text to the last row
         */
        $index = [
            $indexText,
        ];
        $symbol = '';
        $symbolCss = '';
        while (count($index) < count($text)) {
            $index[] = <<<HTML
            <div class="{$symbolCss}">{$symbol}</div>
            HTML;
        }

        $elipsis = Config::getTestNameElipsisText();

        foreach (array_keys($text) as $row) {
            Renderer::render(<<<HTML
                <div class="flex w-full pl-{$widths['left']} space-x-{$widths['padding']}">
                    <span class="w-{$widths['index']} {$indexCss} text-right">
                        {$index[$row]}
                    </span>

                    <span class="w-{$widths['status']} {$statusCss}">
                        {$status[$row]}
                    </span>

                    <span class="w-{$nameWidth} max-w-{$nameWidth} truncate">
                        <div class="flex">
                            <div class="w-auto">{$text[$row]}</div>
                            <span class="w-auto pl-1 {$elipsisCss} content-repeat-['{$elipsis}']"></span>
                        </div>
                    </span>

                    <span class="w-{$widths['time']} {$timeCss}">
                        {$time[$row]}
                    </span>
                </div>
            HTML);
        }

        $this->group->setLastTest($this->getName());
    }

    public function setPassedIfPending(): self
    {
        $this->status = ($this->status === Status::PENDING) ? Status::SUCCESS : $this->status;

        return $this;
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setTime(float|Time|null $time): self
    {
        $time = ($time instanceof Time) ? $time : Time::parse($time);

        $this->time = $time;

        return $this;
    }

    public function setError(Throwable $e): self
    {
        $this->error = $e;

        return $this;
    }

    public function showAdditionalInformation(): bool
    {
        return $this->status->showAdditionalInformation();
    }

    public function renderAdditionalInformation(array &$issues): void
    {
        $issues[$this->status->value] ??= 0;
        $issueNumber = ++$issues[$this->status->value];

        $issueTerm = $this->status->text();
        $statusCss = $this->status->css();
        $inverseStatusCss = $this->status->inverseCss();

        Printer::delimiter();

        Renderer::render(<<<HTML
            <div class="pt-1 px-2">
                <div class="{$inverseStatusCss} px-1">{$issueTerm} #{$issueNumber}</div>
            </div>
        HTML);

        $file = $this->group->getName();
        $name = $this->getName();
        $dataset = $this->getDataset();
        $datasetclass = $this->name->hasDataset() ? '' : 'hidden';

        $icon1 = Config::getFailedTestDelimiter1Text();
        $icon2 = Config::getFailedTestDelimiter2Text();
        $icon3 = Config::getFailedTestDelimiter3Text();
        $iconCss = Config::getFailedTestDelimiterClass();

        Renderer::render(<<<HTML
        <div class="pl-2 pt-1">
            <div class="flex">
                <span class="{$iconCss}">{$icon1}</span> <span class="{$statusCss}">{$file}</span>
                <span class="pl-1 {$iconCss}">{$icon2}</span> <span class="{$statusCss}">{$name}</span>
                <span class="{$datasetclass} ml-1"> <span class="{$iconCss}">{$icon3}</span> <span class="{$statusCss}">{$dataset}</span></span>
            </div>
        </div>
        HTML);

        $exception = null;
        if ($this->hasException() && $this->shouldShowExceptionPreview()) {
            $error = $this->error;

            assert($error !== null);
            $exception = ExceptionPreview::make($error);

            $exception->renderType(indent: 2);
        }

        $statusMessage = trim($this->test->getStatusMessage());

        if ($statusMessage !== '') {
            Renderer::raw("\n  {$statusMessage}\n");
        }

        if ($exception !== null) {
            $exception->render(indent: 2, type: false);
        }
    }

    public function shouldShowExceptionPreview(): bool
    {
        if ($this->error === null) {
            return false;
        }

        if ($this->error instanceof SkippedTestError) {
            return false;
        }

        if ($this->error instanceof IncompleteTestError) {
            return false;
        }

        return true;
    }

    public function hasException(): bool
    {
        return $this->error !== null;
    }

    public function hasAssertionException(): bool
    {
        if ($this->error === null) {
            return false;
        }

        if (
            ($this->error instanceof Warning) ||
            ($this->error instanceof AssertionError) ||
            ($this->error instanceof AssertionFailedError)
        ) {
            return true;
        }

        return false;
    }
}
