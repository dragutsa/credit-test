<?php

declare(strict_types=1);

namespace Common\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Age
{
    private int $value;

    public function __construct(\DateTimeImmutable $birthDate, \DateTimeImmutable $now)
    {
        Assert::lessThan($birthDate, $now);
        $this->value = $now->diff($birthDate)->y;
    }

    public function isSolvent(): bool
    {
        return $this->isAdult() && !$this->isPensioner();
    }

    private function isAdult(): bool
    {
        return $this->value >= 18;
    }

    private function isPensioner(): bool
    {
        return $this->value >= 60;
    }
}