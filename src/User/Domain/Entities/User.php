<?php

namespace User\Domain\Entities;

use Common\EventBus\Contracts\EventsHolder;
use Common\EventBus\Contracts\EventsHolderTrait;
use Common\ValueObjects\Email;
use Common\ValueObjects\Fico;
use Common\ValueObjects\FullName;
use Common\ValueObjects\Money;
use Common\ValueObjects\Phone;
use Common\ValueObjects\Ssn;
use Common\ValueObjects\UserId;
use Doctrine\ORM\Mapping as ORM;
use User\Application\Dto\AppUserChangeEmail;
use User\Domain\Events\UserCreated;
use User\Domain\Repositories\UsersRepository;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class User implements EventsHolder
{
    use EventsHolderTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UserId $userId;

    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private Ssn $ssn;

    #[ORM\Column]
    private Email $email;

    #[ORM\Embedded(class: FullName::class)]
    /** @phpstan-ignore-next-line */
    private FullName $fullName;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'users')]
    /** @phpstan-ignore-next-line */
    private Address $address;

    public function __construct(
        Ssn $ssn,
        Email $email,
        FullName $fullName,
        Address $address,
        \DateTimeImmutable $birthDate,
        Fico $ficoScore,
        Money $monthlyIncome,
        Phone $phone,
    ) {
        $this->userId = UserId::generate($ssn);
        $this->ssn = $ssn;
        $this->email = $email;
        $this->fullName = $fullName;
        $this->address = $address;

        $this->events[] = new UserCreated(
            $this->userId,
            $email,
            $fullName,
            $birthDate,
            $ssn,
            $address->getState(),
            $ficoScore,
            $monthlyIncome,
            $phone,
        );
    }

    public function getId(): UserId
    {
        return $this->userId;
    }

    public function changeEmail(Email $email): self
    {
        if (!$this->email->equals($email)) {
            $this->email = $email;
            $this->events[] = new AppUserChangeEmail($this->userId, $email);
        }

        return $this;
    }
}
