<?php

namespace BradieTilley\Objects;

class Name
{
    public function __construct(protected string $name, protected ?string $dataset = null)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasDataset(): bool
    {
        return $this->dataset !== null;
    }

    public function getDataset(): ?string
    {
        return $this->dataset;
    }

    public static function parse(string $name): array
    {
        $dataset = null;

        // After calculating length, now add colours
        if (preg_match('/^(.+) with \([\'"](.+)[\'"]\)\s*$/', $name, $matches)) {
            $name = trim($matches[1]);
            $dataset = trim($matches[2]);
        } elseif (preg_match('/^(.+) with data set "(.+)"\s*$/', $name, $matches)) {
            $name = trim($matches[1]);
            $dataset = trim($matches[2]);
        }

        return [ ucfirst($name), $dataset ];
    }

    public static function make(string $name): self
    {
        return new self(... static::parse($name));
    } 
}