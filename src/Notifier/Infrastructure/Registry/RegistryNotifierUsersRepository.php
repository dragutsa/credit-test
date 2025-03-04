<?php

namespace Notifier\Infrastructure\Registry;

use Common\ValueObjects\Email;
use Common\ValueObjects\FullName;
use Common\ValueObjects\Name;
use Common\ValueObjects\Phone;
use Common\ValueObjects\Ssn;
use Common\ValueObjects\UserId;
use Notifier\Domain\Entities\NotifierUser;
use Notifier\Domain\Repositories\NotifierUsersRepository;

class RegistryNotifierUsersRepository implements NotifierUsersRepository
{
    /** @var NotifierUser[] */
    private array $items = [];

    public function __construct()
    {
        for ($i = 0; $i < 5; $i++) {
            $user = new NotifierUser(
                UserId::generate(Ssn::fromString('111-22-000' . $i)),
                new FullName(Name::fromString('firstName' . $i), Name::fromString('lastName' . $i)),
                Email::fromString('email@user' . $i . '.com'),
                Phone::fromString('(800) 326-6148')
            );
            $this->items[$user->getId()->toString()] = $user;
        }
    }

    public function save(NotifierUser $user): NotifierUser
    {
        $this->items[$user->getId()->toString()] = $user;

        return $user;
    }

    public function find(UserId $userId): ?NotifierUser
    {
        return $this->items[$userId->toString()] ?? null;
    }

    public function exists(UserId $userId): bool
    {
        return isset($this->items[$userId->toString()]);
    }
}
