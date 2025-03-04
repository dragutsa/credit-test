<?php

declare(strict_types=1);

namespace Credit\Domain\Helpers;

final readonly class CreditRandomizer implements Randomizer
{
    public function throwDice(): bool
    {
        return mt_rand(0, 1) === 1;
    }
}