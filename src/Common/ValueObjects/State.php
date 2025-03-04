<?php

declare(strict_types=1);

namespace Common\ValueObjects;

use Credit\Domain\Deciders\Decider;

enum State: string
{
    case AL = 'Alabama';
    case CA = 'California';
    case NY = 'New York';
    case NV = 'Nevada';
    case TX = 'Texas';
    case FL = 'Florida';

    //... all states

    public function isExtraRate(): bool
    {
        return $this === self::CA;
    }

    public function isCreditAvailable(Decider $decider): bool
    {
        if ($this === self::NY) {
            return $decider->decide();
        }
        return in_array($this, [self::CA, self::NV]);
    }
}