<?php

namespace Credit\Infrastructure\Registry;

use Common\ValueObjects\Fico;
use Common\ValueObjects\Money;
use Common\ValueObjects\Ssn;
use Common\ValueObjects\State;
use Common\ValueObjects\UserId;
use Credit\Domain\Entities\CreditUser;
use Credit\Domain\Repositories\CreditUsersRepository;

class RegistryCreditUsersRepository implements CreditUsersRepository
{
    /** @var CreditUser[] */
    private array $items = [];

    public function __construct()
    {
        $states = [
            State::CA,
            State::NY,
            State::TX,
            State::FL,
            State::NV,
        ];
        for ($i = 0; $i < 5; $i++) {
            $ssn = Ssn::fromString('111-22-000' . $i);
            $user = new CreditUser(
                UserId::generate($ssn),
                new \DateTimeImmutable('1999-01-0' . $i),
                $ssn,
                $states[$i],
                new Fico(700 + $i),
                Money::fromIntDollars(1000 + $i),
            );
            $this->items[$user->getId()->toString()] = $user;
        }
    }

    public function save(CreditUser $user): CreditUser
    {
        $this->items[$user->getId()->toString()] = $user;

        return $user;
    }

    public function find(UserId $userId): ?CreditUser
    {
        return $this->items[$userId->toString()] ?? null;
    }

    public function exists(UserId $userId): bool
    {
        return isset($this->items[$userId->toString()]);
    }
}
