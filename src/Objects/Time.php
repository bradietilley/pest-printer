<?php

namespace BradieTilley\PestPrinter\Objects;

class Time
{
    private TimeGrading $grading;

    public function __construct(protected ?float $time = null)
    {
        $this->grading = TimeGrading::determine($time);
    }

    public function css(): string
    {
        return $this->grading->getConfigurationClass();
    }

    public function format(): string
    {
        if ($this->time === null) {
            return 'unknown';
        }

        return number_format($this->time, decimals: 3).'s';
    }

    public static function parse(?float $time): self
    {
        return new self($time);
    }
}
