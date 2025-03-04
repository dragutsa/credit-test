<?php

declare(strict_types=1);

namespace Credit\Domain\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Rate
{
    private const int EXTRA_RATE_ADDITION = 1149;

    public static function fromFloat(float $rate): self
    {
        return new self((int) round($rate * 100));
    }

    /** @param non-negative-int $rate */
    public static function fromBaseUnits(int $rate): self
    {
        return new self($rate);
    }

    /** @param non-negative-int $rate */
    private function __construct(private int $rate)
    {
        Assert::greaterThanEq($rate, 0);
    }

    /** @return non-negative-int */
    public function toBaseUnits(): int
    {
        return $this->rate;
    }

    public function addExtraRate(): self
    {
        return new self($this->rate + self::EXTRA_RATE_ADDITION);
    }
}