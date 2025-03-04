<?php

namespace User\Infrastructure\Registry;

use Common\ValueObjects\State;
use User\Domain\Entities\Address;
use User\Domain\Repositories\AddressesRepository;
use User\Domain\ValueObjects\AddressId;

class RegistryAddressesRepository implements AddressesRepository
{
    /** @var Address[] */
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
            $address = new Address(
                'street ' . $i,
                'city ' . $i,
                $states[$i],
                'zipCode' . $i,
            );
            $this->items[$address->getId()->toString()] = $address;
        }
    }

    public function find(AddressId $addressId): ?Address
    {
        return $this->items[$addressId->toString()] ?? null;
    }

    /** @inheritdoc */
    public function all(): array
    {
        return $this->items;
    }
}
