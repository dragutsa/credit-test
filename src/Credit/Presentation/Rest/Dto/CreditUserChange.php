<?php

declare(strict_types=1);

namespace Credit\Presentation\Rest\Dto;

use Common\ValueObjects\Fico;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreditUserChange
{
    public function __construct(
        #[Assert\Date]
        public ?string $birthDate,
        #[Assert\Range(min: Fico::MIN, max: Fico::MAX)]
        public ?int $ficoScore,
        #[Assert\GreaterThanOrEqual(0)]
        public ?int $monthlyIncome,
    ) {
    }
}