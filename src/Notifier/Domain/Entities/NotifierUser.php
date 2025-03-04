<?php

namespace Notifier\Domain\Entities;

use Common\ValueObjects\Email;
use Common\ValueObjects\FullName;
use Common\ValueObjects\Phone;
use Common\ValueObjects\UserId;
use Doctrine\ORM\Mapping as ORM;
use Notifier\Domain\Repositories\NotifierUsersRepository;

#[ORM\Entity(repositoryClass: NotifierUsersRepository::class)]
class NotifierUser
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UserId $userId;

    #[ORM\Embedded(class: FullName::class)]
    /** @phpstan-ignore-next-line */
    private FullName $fullName;

    #[ORM\Column]
    private Email $email;

    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private Phone $phone;

    public function __construct(
        UserId $userId,
        FullName $fullName,
        Email $email,
        Phone $phone,
    ) {
        $this->userId = $userId;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->phone = $phone;
    }

    public function getId(): UserId
    {
        return $this->userId;
    }

    public function changeEmail(Email $email): self
    {
        if (!$this->email->equals($email)) {
            $this->email = $email;
        }

        return $this;
    }
}
