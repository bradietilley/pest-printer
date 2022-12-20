<?php

namespace BradieTilley;

use BradieTilley\Exceptions\PrinterException;
use BradieTilley\Objects\Group;
use BradieTilley\Objects\Single;
use BradieTilley\Objects\Status;
use BradieTilley\Objects\Time;
use Illuminate\Support\Collection;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use ReflectionClass;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Termwind\Termwind;
use Throwable;
use function Termwind\{render};

class Printer implements \PHPUnit\TextUI\ResultPrinter
{
    protected static ?OutputInterface $renderer = null;

    protected ?Group $group = null;
    protected ?Single $single = null;

    protected static string $groupClass = Group::class;
    protected static string $singleClass = Single::class;

    protected ?Collection $groups = null;

    public function printResult(TestResult $result): void
    {
        $this->groups ??= Collection::make([]);

        $tests = $this->groups
            ->map(fn (Group $group) => $group->tests())
            ->collapse()
            ->groupBy(fn (Single $single) => $single->getStatus()->value)
            ->map(fn (Collection $singles, string $status) => [
                'count' => $singles->count(),
                'status' => Status::from($status),
            ])
            ->sortBy(fn (array $data) => $data['status']->value)
            ->sortBy('count', descending: true)
            ->values();

        $status = Status::getLowestDemoninator(
            $tests->pluck('status')->all(),
        );

        $text = $tests
            ->map(function (array $data) {
                $text = sprintf(
                    '%d %s',
                    $data['count'],
                    $data['status']->textPast(),
                );

                $statusCss = $data['status']->css();

                return <<<HTML
                    <span class="{$statusCss}">{$text}</span>
                HTML;
            })
            ->implode(', ');

        $css = $status->inverseCss();
        $time = $result->time();
        $count = $result->count();

        $average = ($count === 0) ? new Time() : Time::parse($time / $count);
        $timeCss = $average->css();

        $time = number_format($time, decimals: 3);

        $testsTerm = ($count === 1) ? 'test' : 'tests';


        if ($tests->isEmpty()) {
            $text = 'No tests matched';
        } else {
            if (($status !== Status::SUCCESS)) {
                // 1: Display each error in detail
                $this->groups->each(fn (Group $group) => $group->printAdditionalInformation());

                // 2: delimiter
                self::delimiter();
            }
        }

        // 3: Summary
        Renderer::render(<<<HTML
            <div class="pt-2 px-2 pb-2">
                <div class="px-2">
                    <span class="w-7">Tests:</span> <span>{$text}</span>
                </div>
                <div class="px-2">
                    <span class="w-7">Total:</span> <span class="">{$count} {$testsTerm}</span>
                </div>
                <div class="px-2">
                    <span class="w-7">Time:</span> <span class="{$timeCss}">{$time}s</span>
                </div>
            </div>
        HTML);
    }

    public function write(string $buffer): void
    {
        Renderer::raw($buffer);
    }

    public static function setGroup(string $class): void
    {
        if (! class_exists($class)) {
            throw new PrinterException('Cannot register Test Group class that does not exist.');
        }

        $reflection = new ReflectionClass($class);
        if (! $reflection->isSubclassOf(Group::class)) {
            throw new PrinterException('Cannot register Test Group class that does not extend \BradieTilley\Objects\Group');
        }

        static::$groupClass = $class;
    }

    public static function setSingle(string $class): void
    {
        if (! class_exists($class)) {
            throw new PrinterException('Cannot register Test Group class that does not exist.');
        }

        $reflection = new ReflectionClass($class);
        if (! $reflection->isSubclassOf(Single::class)) {
            throw new PrinterException('Cannot register Test Group class that does not extend \BradieTilley\Objects\Group');
        }

        static::$singleClass = $class;
    }

    public function makeGroup(TestSuite $test): Group
    {
        $class = static::$groupClass;
        $group = $class::make($test);

        $this->group = $group;
        $this->groups ??= Collection::make([]);
        $this->groups->push($group);

        return $group;
    }

    public function makeSingle(TestCase $test): Single
    {
        $class = static::$singleClass;

        return $class::make($test, $this->group);
    }

    public function addError(Test $test, Throwable $t, float $time): void
    {
        $this->single
            ->setTime($time)
            ->setError($t)
            ->setStatus(Status::ERROR);
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
        $this->single
            ->setTime($time)
            ->setError($e)
            ->setStatus(Status::WARNING);
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        $this->single
            ->setTime($time)
            ->setError($e)
            ->setStatus(Status::FAILED);
    }

    public function addIncompleteTest(Test $test, Throwable $t, float $time): void
    {
        $this->single
            ->setTime($time)
            ->setError($t)
            ->setStatus(Status::INCOMPLETE);
    }

    public function addRiskyTest(Test $test, Throwable $t, float $time): void
    {
        $this->single
            ->setTime($time)
            ->setError($t)
            ->setStatus(Status::RISKY);
    }

    public function addSkippedTest(Test $test, Throwable $t, float $time): void
    {
        $this->single
            ->setTime($time)
            ->setError($t)
            ->setStatus(Status::SKIPPED);
    }

    public function startTestSuite(TestSuite $suite): void
    {
        try {
            if (strlen($suite->getName()) === 0) {
                return;
            }

            $this->makeGroup($suite);
            $this->group->start();
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    public function endTestSuite(TestSuite $suite): void
    {
        try {
            if ($this->group === null) {
                return;
            }

            $this->group->end();
            $this->group = null;
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    /**
     * @param \PHPUnit\Framework\TestCase $test
     */
    public function startTest(Test $test): void
    {
        try {
            $this->single = $this->makeSingle($test);
            $this->single->start();

            $this->group->addTest($this->single);
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    /**
     * @param \PHPUnit\Framework\TestCase $test
     */
    public function endTest(Test $test, float $time): void
    {
        try {
            $this->single->setPassedIfPending()->setTime($time)->end();
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    public static function delimiter(): void
    {
        Renderer::render(<<<HTML
            <div class="mt-1 text-zinc-700 content-repeat-['-']"></div>
        HTML);
    }
}
