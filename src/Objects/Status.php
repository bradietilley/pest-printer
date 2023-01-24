<?php

namespace BradieTilley\PestPrinter\Objects;

use BradieTilley\PestPrinter\PestPrinterConfig;

enum Status: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case WARNING = 'warning';
    case ERROR = 'error';
    case SKIPPED = 'skipped';
    case INCOMPLETE = 'incomplete';
    case RISKY = 'risky';
    case UNKNOWN = 'unknown';

    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'P',
            self::SUCCESS => '✓',
            self::FAILED => '✗',
            self::WARNING => '!',
            self::ERROR => 'E',
            self::SKIPPED => 'S',
            self::INCOMPLETE => 'I',
            self::RISKY => 'R',
            self::UNKNOWN => '?',
        };
    }

    public function showStatusMessageInline(): bool
    {
        return match ($this) {
            self::PENDING => false,
            self::SUCCESS => false,
            self::FAILED => false,
            self::ERROR => false,
            self::WARNING => true,
            self::SKIPPED => true,
            self::INCOMPLETE => true,
            self::RISKY => true,
            self::UNKNOWN => true,
        };
    }

    public function css(): string
    {
        return PestPrinterConfig::color("text-{$this->color()}");
    }

    public function inverseCss(): string
    {
        return PestPrinterConfig::color("bg-{$this->color()}-700").' text-white';
    }

    public function color(): string
    {
        return PestPrinterConfig::statusColor($this->value, 'grey');
    }

    public function textPast(): string
    {
        return match ($this) {
            self::SUCCESS => 'Passed',
            self::FAILED => 'Failed',
            self::ERROR => 'Errored',
            self::PENDING => 'Pending',
            self::WARNING => 'Warned',
            self::SKIPPED => 'Skipped',
            self::INCOMPLETE => 'Incompleted',
            self::RISKY => 'Risky',
            self::UNKNOWN => 'Unknown',
        };
    }

    public function text(): string
    {
        return match ($this) {
            self::SUCCESS => 'Pass',
            self::FAILED => 'Failure',
            self::ERROR => 'Error',
            self::PENDING => 'Pending',
            self::WARNING => 'Warning',
            self::SKIPPED => 'Skip',
            self::INCOMPLETE => 'Incomplete',
            self::RISKY => 'Risky',
            self::UNKNOWN => 'Unknown',
        };
    }

    public function group(): self
    {
        return match ($this) {
            self::SUCCESS => self::SUCCESS,
            self::FAILED => self::FAILED,
            self::ERROR => self::FAILED,
            self::PENDING => self::WARNING,
            self::WARNING => self::WARNING,
            self::SKIPPED => self::WARNING,
            self::INCOMPLETE => self::WARNING,
            self::RISKY => self::WARNING,
            self::UNKNOWN => self::WARNING,
        };
    }

    /**
     * @param  array<Status>  $statuses
     * @return Status
     */
    public static function getLowestDemoninator(array $statuses): self
    {
        $all = [];

        foreach ($statuses as $status) {
            $all[] = $status->group();
        }

        if (in_array(self::FAILED, $all)) {
            return self::FAILED;
        }

        if (in_array(self::WARNING, $all)) {
            return self::WARNING;
        }

        if (in_array(self::SUCCESS, $all)) {
            return self::SUCCESS;
        }

        return self::UNKNOWN;
    }

    public function pluralTerm(): string
    {
        return match ($this) {
            self::FAILED => 'Failures',
            self::WARNING => 'Warnings',
            self::SUCCESS => 'Successes',
            default => 'Unknowns',
        };
    }
}
