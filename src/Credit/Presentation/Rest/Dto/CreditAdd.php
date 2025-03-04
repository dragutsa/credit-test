<?php

declare(strict_types=1);

namespace Credit\Presentation\Rest\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreditAdd
{
    public function __construct(
        #[Assert\Uuid]
        public string $productId,
    ) {
    }
}