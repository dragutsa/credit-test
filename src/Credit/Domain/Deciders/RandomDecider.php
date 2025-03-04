<?php

declare(strict_types=1);

namespace Credit\Domain\Deciders;

final readonly class RandomDecider implements Decider
{
    public function decide(): bool
    {
        return mt_rand(0, 1) === 1;
    }
}