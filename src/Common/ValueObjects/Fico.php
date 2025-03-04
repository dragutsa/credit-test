<?php

declare(strict_types=1);

namespace Common\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Fico
{
    public const int MIN = 300;
    public const int MAX = 850;

    private const int MIN_SOLVENT = 500;

    public function __construct(private int $score)
    {
        Assert::range($this->score, self::MIN, self::MAX);
    }

    public function toInt(): int
    {
        return $this->score;
    }

    public function equals(self $fico): bool
    {
        return $this->score === $fico->score;
    }

    public function isSolvent(): bool
    {
        return $this->score > self::MIN_SOLVENT;
    }
}