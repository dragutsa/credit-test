<?php

declare(strict_types=1);

namespace Credit\Domain\Events;

use Common\ValueObjects\UserId;
use Credit\Domain\ValueObjects\CreditId;
use Credit\Domain\ValueObjects\ProductId;
use Credit\Domain\ValueObjects\Rate;

final readonly class CreditAdded
{
    public function __construct(
        public UserId $userId,
        public CreditId $creditId,
        public ProductId $productId,
        public Rate $rate,
    ) {
    }
}