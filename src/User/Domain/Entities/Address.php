<?php

declare(strict_types=1);

namespace User\Domain\Entities;

use Common\ValueObjects\State;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use User\Domain\ValueObjects\AddressId;

final readonly class Address
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private AddressId $addressId;

    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private string $street;

    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private string $city;

    #[ORM\Column]
    private State $state;

    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private string $zipCode;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'address')]
    /** @phpstan-ignore-next-line */
    private Collection $users;

    public function __construct(
        string $street,
        string $city,
        State $state,
        string $zipCode,
    ) {
        $this->addressId = AddressId::generate($street, $city, $state, $zipCode);
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
        $this->users = new ArrayCollection;
    }

    public function getId(): AddressId
    {
        return $this->addressId;
    }

    public function getState(): State
    {
        return $this->state;
    }
}