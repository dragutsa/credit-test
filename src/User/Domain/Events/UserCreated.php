<?php

declare(strict_types=1);

namespace User\Domain\Events;

use Common\ValueObjects\Email;
use Common\ValueObjects\Fico;
use Common\ValueObjects\FullName;
use Common\ValueObjects\Money;
use Common\ValueObjects\Phone;
use Common\ValueObjects\Ssn;
use Common\ValueObjects\State;
use Common\ValueObjects\UserId;

final readonly class UserCreated
{
    public function __construct(
        public UserId $userId,
        public Email $email,
        public FullName $fullName,
        public \DateTimeImmutable $birthDate,
        public Ssn $ssn,
        public State $state,
        public Fico $ficoScore,
        public Money $monthlyIncome,
        public Phone $phone,
    ) {
    }
}