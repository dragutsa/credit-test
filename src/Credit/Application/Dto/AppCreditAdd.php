<?php

declare(strict_types=1);

namespace Credit\Application\Dto;

use Common\ValueObjects\UserId;
use Credit\Domain\ValueObjects\ProductId;

final readonly class AppCreditAdd
{
    public function __construct(
        public UserId $userId,
        public ProductId $productId,
        public \DateTimeImmutable $dateOfIssue,
    ) {
    }
}