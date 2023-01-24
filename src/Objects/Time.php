<?php

namespace BradieTilley\PestPrinter\Objects;

use BradieTilley\PestPrinter\PestPrinterConfig;

class Time
{
    public const FAST = 0.2;

    public const OKAY = 0.75;

    public const SLOW = INF;

    public function __construct(protected ?float $time = null)
    {
    }

    public function css(): string
    {
        return PestPrinterConfig::color(
            match (true) {
                is_null($this->time) => 'text-gray-500',
                $this->time <= self::FAST => 'text-green-500',
                $this->time <= self::OKAY => 'text-amber-500',
                $this->time <= self::SLOW => 'text-red-500',
                default => 'text-gray-500',
            },
        );
    }

    public function inverseCss(): string
    {
        return PestPrinterConfig::color(
            match (true) {
                is_null($this->time) => 'bg-gray-800 text-white',
                $this->time <= self::FAST => 'bg-green-800 text-white',
                $this->time <= self::OKAY => 'bg-amber-800 text-white',
                $this->time <= self::SLOW => 'bg-red-800 text-white',
                default => 'bg-gray-800 text-white',
            },
        );
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
