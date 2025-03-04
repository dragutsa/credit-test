<?php

declare(strict_types=1);

namespace User\Application\Dto;

use Common\ValueObjects\Email;
use Common\ValueObjects\Fico;
use Common\ValueObjects\FullName;
use Common\ValueObjects\Money;
use Common\ValueObjects\Phone;
use Common\ValueObjects\Ssn;
use User\Domain\ValueObjects\AddressId;

final readonly class AppUserCreate
{
    public function __construct(
        public Ssn $ssn,
        public Email $email,
        public FullName $fullName,
        public AddressId $addressId,
        public \DateTimeImmutable $birthDate,
        public Fico $ficoScore,
        public Money $monthlyIncome,
        public Phone $phone,
    ) {
    }
}