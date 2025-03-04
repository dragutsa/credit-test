<?php

declare(strict_types=1);

namespace User\Presentation\Rest\Dto;

use Common\ValueObjects\Fico;
use Common\ValueObjects\Phone;
use Common\ValueObjects\Ssn;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UserCreate
{
    public function __construct(
        #[Assert\Regex(Ssn::PATTERN)]
        public string $ssn,
        #[Assert\Email]
        public string $email,
        #[Assert\Length(min: 2, max: 255)]
        public string $firstName,
        #[Assert\Length(min: 2, max: 255)]
        public string $lastName,
        #[Assert\Uuid]
        public string $addressId,
        #[Assert\Date]
        public string $birthDate,
        #[Assert\Range(min: Fico::MIN, max: Fico::MAX)]
        public int $ficoScore,
        #[Assert\GreaterThanOrEqual(0)]
        public int $monthlyIncome,
        #[Assert\Regex(Phone::PATTERN)]
        public string $phone,
    ) {
    }
}