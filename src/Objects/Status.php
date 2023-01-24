<?php

namespace BradieTilley\PestPrinter\Objects;

use BradieTilley\PestPrinter\Config;

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
        return Config::getStatusIcon($this);
    }

    public function showStatusMessageInline(): bool
    {
        return Config::getStatusShowMessageInline($this);
    }

    public function css(): string
    {
        return Config::getStatusPrimaryCss($this);
    }

    public function inverseCss(): string
    {
        return Config::getStatusInverseCss($this);
    }

    public function color(): string
    {
        return Config::getStatusColor($this);
    }

    public function textPast(): string
    {
        return Config::getStatusTextPastTense($this);
    }

    public function text(): string
    {
        return Config::getStatusTextPresentTense($this);
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
