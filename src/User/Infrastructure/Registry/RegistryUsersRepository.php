<?php

namespace User\Infrastructure\Registry;

use Common\ValueObjects\Email;
use Common\ValueObjects\Fico;
use Common\ValueObjects\FullName;
use Common\ValueObjects\Money;
use Common\ValueObjects\Name;
use Common\ValueObjects\Phone;
use Common\ValueObjects\Ssn;
use Common\ValueObjects\UserId;
use User\Domain\Entities\User;
use User\Domain\Repositories\AddressesRepository;
use User\Domain\Repositories\UsersRepository;

class RegistryUsersRepository implements UsersRepository
{
    /** @var User[] */
    private array $items = [];

    public function __construct(AddressesRepository $addressesRepository)
    {
        $addresses = $addressesRepository->all();
        reset($addresses);
        for ($i = 0; $i < 5; $i++) {
            $user = new User(
                Ssn::fromString('111-22-000' . $i),
                Email::fromString('email@user' . $i . '.com'),
                new FullName(Name::fromString('firstName' . $i), Name::fromString('lastName' . $i)),
                current($addresses),
                new \DateTimeImmutable('2000-01-0' . $i),
                new Fico(700 + $i),
                Money::fromIntDollars(1000 + $i),
                Phone::fromString('(800) 326-6148')
            );
            $user->releaseEvents();
            $this->items[$user->getId()->toString()] = $user;
            next($addresses);
        }
    }

    public function save(User $user): User
    {
        $this->items[$user->getId()->toString()] = $user;

        return $user;
    }

    public function find(UserId $userId): ?User
    {
        return $this->items[$userId->toString()] ?? null;
    }

    public function exists(UserId $userId): bool
    {
        return isset($this->items[$userId->toString()]);
    }
}
