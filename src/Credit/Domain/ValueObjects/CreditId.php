<?php

declare(strict_types=1);

namespace Credit\Domain\ValueObjects;

use Common\ValueObjects\UserId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class CreditId
{
    public static function generate(UserId $userId, ProductId $productId): self
    {
        return new self(
            Uuid::uuid5('4b477069-22e0-4b44-8bb0-000000000004', $userId->toString() . $productId->toString())
        );
    }

    private function __construct(private UuidInterface $id)
    {
    }

    public function toString(): string
    {
        return $this->id->toString();
    }
}