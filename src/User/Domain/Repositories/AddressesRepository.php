<?php

declare(strict_types=1);

namespace User\Domain\Repositories;

use User\Domain\Entities\Address;
use User\Domain\ValueObjects\AddressId;

interface AddressesRepository
{
    public function find(AddressId $addressId): ?Address;

    /** @return list<Address> */
    public function all(): array;
}