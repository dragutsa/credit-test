<?php

declare(strict_types=1);

namespace Credit\Application\Dto;

final readonly class CreditCheckResult
{
    public function __construct(
        public bool $success,
        public ?string $error = null,
    ) {
    }
}