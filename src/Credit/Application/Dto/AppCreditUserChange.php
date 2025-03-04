<?php

declare(strict_types=1);

namespace Credit\Application\Dto;

use Common\ValueObjects\Fico;
use Common\ValueObjects\Money;
use Common\ValueObjects\UserId;

final readonly class AppCreditUserChange
{
    public function __construct(
        public UserId $userId,
        public ?\DateTimeImmutable $birthDate,
        public ?Fico $ficoScore,
        public ?Money $monthlyIncome,
    ) {
    }
}