<?php

namespace BradieTilley\PestPrinter\Objects;

use BradieTilley\PestPrinter\Config;
use BradieTilley\PestPrinter\Renderer;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestSuite;

class Group
{
    /** @var Collection<Single> */
    protected Collection $tests;

    protected ?string $lastTest = null;

    protected bool $running = false;

    protected static int $errors = 0;

    public function __construct(public readonly TestSuite $suite)
    {
        $this->tests = Collection::make([]);
    }

    public static function make(TestSuite $suite): self
    {
        return new self($suite);
    }

    public function addTest(Single $test): self
    {
        $this->tests->push($test);
        $test->setIndex($this->tests->count(), $this->count());

        return $this;
    }

    public function setLastTest(string $lastTest): self
    {
        $this->lastTest = $lastTest;

        return $this;
    }

    public function getLastTest(): ?string
    {
        return $this->lastTest;
    }

    public function tests(): Collection
    {
        return $this->tests;
    }

    public function getName(): string
    {
        return Str::of($this->suite->getName())
            ->replaceMatches('/P\\\\Tests\\\\/', '', 1)
            ->toString();
    }

    public function isRunning(): bool
    {
        return $this->running;
    }

    public function count(): int
    {
        return $this->suite->count();
    }

    public function status(): Status
    {
        return Status::getLowestDemoninator(
            $this->tests()
                ->map(fn (Single $test) => $test->getStatus())
                ->unique()
                ->all(),
        );
    }

    public function start(): void
    {
        $this->running = true;

        $title = $this->getName();
        $gray = Config::getTestNameClass();

        Renderer::render(<<<HTML
            <div class="pl-2 py-1">
                <em class="{$gray}">
                    &nbsp;{$title}&nbsp;
                </em>
            </div>
        HTML);
    }

    public function end(): void
    {
        $this->running = false;

        $status = $this->status();
        $statusCss = $status->inverseCss();
        $statusText = $status->text();

        $title = $this->getName();

        Renderer::render(<<<HTML
            <div class="pl-2 pt-2 pb-2">
                <div class="w-12 text-right">
                    <div class="px-1 {$statusCss}">{$statusText}</div>
                </div>
                <em class="ml-1">
                    {$title}
                </em>
            </div>
        HTML);
    }

    public function printAdditionalInformation(): void
    {
        $issues = [];

        foreach ($this->tests as $test) {
            if ($test->showAdditionalInformation()) {
                $test->renderAdditionalInformation($issues);
            }
        }
    }
}
