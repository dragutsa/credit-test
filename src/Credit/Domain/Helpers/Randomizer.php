<?php

declare(strict_types=1);

namespace Credit\Domain\Helpers;

interface Randomizer
{
    public function throwDice(): bool;
}