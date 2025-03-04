<?php

declare(strict_types=1);

namespace User\Domain\ValueObjects;

use Common\ValueObjects\State;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class AddressId
{
    /**
     * @param non-empty-string $street
     * @param non-empty-string $city
     * @param non-empty-string $zipCode
     */
    public static function generate(
        string $street,
        string $city,
        State $state,
        string $zipCode,
    ): self {
        return new self(
            Uuid::uuid5('4b477069-22e0-4b44-8bb0-000000000003', $street . $city . $state->value . $zipCode)
        );
    }

    /** @param non-empty-string $uuid */
    public static function fromString(string $uuid): self
    {
        return new self(Uuid::fromString($uuid));
    }

    private function __construct(private UuidInterface $id)
    {
    }

    public function toString(): string
    {
        return $this->id->toString();
    }
}