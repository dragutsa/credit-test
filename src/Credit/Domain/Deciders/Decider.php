<?php

declare(strict_types=1);

namespace Credit\Domain\Deciders;

/** Decorators place */
interface Decider
{
    public function decide(): bool;
}