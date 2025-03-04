<?php

declare(strict_types=1);

namespace Credit\Domain\ValueObjects;

use Common\ValueObjects\Name;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class ProductId
{
    public static function generate(Name $name): self
    {
        return new self(Uuid::uuid5('4b477069-22e0-4b44-8bb0-000000000001', $name->toString()));
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