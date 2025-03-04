<?php

declare(strict_types=1);

namespace Common\ValueObjects;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class UserId
{
    public static function generate(Ssn $ssn): self
    {
        return new self(Uuid::uuid5('4b477069-22e0-4b44-8bb0-000000000002', $ssn->toString()));
    }

    public static function fromString(string $id): self
    {
        return new self(Uuid::fromString($id));
    }

    private function __construct(private UuidInterface $id)
    {
    }

    public function toString(): string
    {
        return $this->id->toString();
    }
}