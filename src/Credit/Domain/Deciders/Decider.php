<?php

declare(strict_types=1);

namespace Credit\Domain\Deciders;

/**
 * Decorators or Visitor
 */
interface Decider
{
    public function decide(): bool;
}