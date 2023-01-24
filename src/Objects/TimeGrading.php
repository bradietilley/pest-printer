<?php

namespace BradieTilley\PestPrinter\Objects;

use BradieTilley\PestPrinter\Config;
use Illuminate\Support\Collection;

enum TimeGrading: string
{
    case FAST = 'fast';
    case OKAY = 'okay';
    case SLOW = 'slow';
    case NULL = 'null';

    public function getConfigurationTime(): float
    {
        return match ($this) {
            self::FAST => Config::getTimeGradeFastTime(),
            self::OKAY => Config::getTimeGradeOkayTime(),
            self::SLOW => Config::getTimeGradeSlowTime(),
            self::NULL => PHP_FLOAT_MAX,
        };
    }

    public function getConfigurationClass(): string
    {
        return match ($this) {
            self::FAST => Config::getTimeGradeFastClass(),
            self::OKAY => Config::getTimeGradeOkayClass(),
            self::SLOW => Config::getTimeGradeSlowClass(),
            self::NULL => Config::getTimeGradeNullClass(),
        };
    }

    public static function determine(int|float|null $seconds): TimeGrading
    {
        if ($seconds === null) {
            return self::NULL;
        }

        $rank = Collection::make(self::cases())
            ->sortBy(fn (TimeGrading $grading) => $grading->getConfigurationTime());

        foreach ($rank as $grading) {
            if ($seconds <= $grading->getConfigurationTime()) {
                return $grading;
            }
        }

        return self::NULL;
    }
}
