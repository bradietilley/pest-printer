<?php

namespace BradieTilley\PestPrinter\Objects;

use BradieTilley\PestPrinter\Config;
use BradieTilley\PestPrinter\Renderer;
use PHPUnit\Framework\ExceptionWrapper;
use PHPUnit\Framework\ExpectationFailedException;
use Throwable;

class ExceptionPreview
{
    public function __construct(public readonly Throwable $exception)
    {
    }

    public static function make(Throwable $exception): self
    {
        return new self($exception);
    }

    public function readFile(): string
    {
        return @file_get_contents($this->exception->getFile()) ?: '';
    }

    public function extractLines(int $from, int $to, string $file): array
    {
        if (! file_exists($file)) {
            return [];
        }

        $handler = fopen($file, 'r');

        if (! $handler) {
            throw new \Exception('Failed to read file '.$file);
        }

        $row = 0;
        $rows = [];

        while (($line = fgets($handler)) !== false) {
            $row++;

            if ($row >= $from && $row <= $to) {
                $rows[$row] = $line;
            }
        }

        fclose($handler);

        return $rows;
    }

    public function extractAroundLine(int $line, string $file): array
    {
        return $this->extractLines(
            max(1, $line - 5),
            $line + 5,
            $file,
        );
    }

    public function findTraceThatIsNotPhpUnitOrPest(): ?array
    {
        foreach ($this->exception->getTrace() as $trace) {
            /** @var array<string, mixed> $trace */
            $file = $trace['file'];
            /** @var string $file */
            if (str_contains($file, '/vendor/phpunit/phpunit/') || str_contains($file, '/vendor/pestphp/pest/')) {
                continue;
            }

            return $trace;
        }

        return null;
    }

    public function getFriendlyClassName(): string
    {
        $class = get_class($this->exception);

        if ($this->exception instanceof ExpectationFailedException) {
            $class = $this->exception->getComparisonFailure() ?? $this->exception;
            $class = get_class($class);
        }

        if ($this->exception instanceof ExceptionWrapper) {
            $class = $this->exception->getClassName();
        }

        return $class;
    }

    public function compileLines(array $rows): string
    {
        // remove line number keys
        $rows = array_values($rows);

        // Render this as text, don't bother with colourisation (@todo fix)
        $safeDisplay = [
            "\t" => '    ',
            ' ' => '&nbsp;',
            '<' => '&lt;',
            '>' => '&gt;',
        ];

        $rows = array_map(fn ($lineText) => rtrim($lineText, "\n\r").'&nbsp;', $rows);
        $lines = implode(PHP_EOL, $rows);
        $lines = str_replace(array_keys($safeDisplay), array_values($safeDisplay), $lines);

        return $lines;
    }

    public function render(int $indent = 0, bool $type = true): void
    {
        $trace = $this->findTraceThatIsNotPhpUnitOrPest();

        $line = $trace['line'] ?? $this->exception->getLine();
        $file = $trace['file'] ?? $this->exception->getFile();

        // Extract about 10 rows of code with the affected line in the middle
        $rows = $this->extractAroundLine($line, $file);

        // Determine the first line of the code snippet
        $firstLine = array_key_first($rows);

        // Compile the code snippet as a string
        $lines = $this->compileLines($rows);

        // Get the name of the relevant exception
        $class = $this->getFriendlyClassName();

        $typeClass = $type ? 'flex' : 'hidden';
        $gray = Config::getExceptionPreviewLabelClass();

        $html = <<<HTML
        <div class="pl-{$indent}">
            <div class="{$typeClass}">
                <span class="px-1 {$gray}">Type:</span>
                <span class="ml-1">{$class}</span>
            </div>

            <div class="flex">
                <span class="px-1 {$gray}">File:</span>
                <span class="ml-1">{$this->exception->getFile()}</span>
            </div>

            <div class="flex mb-1">
                <span class="px-1 {$gray}">Line:</span>
                <span class="ml-1">{$this->exception->getLine()}</span>
            </div>

            <code line="{$line}" start-line="{$firstLine}">
            {$lines}
            </code>
        </div>
        HTML;

        Renderer::render($html);
    }

    public function renderType(int $indent = 0, Status $status = Status::ERROR): void
    {
        // Get the name of the relevant exception
        $class = $this->getFriendlyClassName();
        $errorCss = $status->inverseCss();

        Renderer::render(<<<HTML
            <div class="pl-{$indent}">
                <span class="px-1 {$errorCss}">{$class}</span>
            </div>
        HTML);
    }
}
