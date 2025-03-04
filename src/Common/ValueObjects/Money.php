<?php

declare(strict_types=1);

namespace Common\ValueObjects;

final readonly class Money
{
    private const int MIN_MONTHLY_INCOME_BASE_UNITS = 100000;
    private const int SCALE = 100;

    public static function minMonthlyIncome(): self
    {
        return self::fromBaseUnits(self::MIN_MONTHLY_INCOME_BASE_UNITS);
    }

    public static function fromFloat(float $amount): self
    {
        return new self((int) round($amount * 100));
    }

    /** @param non-negative-int $amount */
    public static function fromIntDollars(int $amount): self
    {
        return new self($amount * self::SCALE);
    }

    /** @param non-negative-int $amount */
    public static function fromBaseUnits(int $amount): self
    {
        return new self($amount);
    }

    private function __construct(private int $amount)
    {
    }

    public function equals(self $money): bool
    {
        return $this->amount === $money->amount;
    }

    public function isSolvent(): bool
    {
        return $this->amount >= self::MIN_MONTHLY_INCOME_BASE_UNITS;
    }

    public function toBaseUnits(): int
    {
        return $this->amount;
    }
}