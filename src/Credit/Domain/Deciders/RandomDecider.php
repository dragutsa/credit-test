<?php

declare(strict_types=1);

namespace Credit\Domain\Deciders;

/**
 * Decorators or Visitor
 */
final readonly class RandomDecider implements Decider
{
    public function decide(): bool
    {
        return mt_rand(0, 1) === 1;
    }
}