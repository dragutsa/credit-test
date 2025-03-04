<?php

declare(strict_types=1);

namespace User\Presentation\Rest\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UserChangeEmail
{
    public function __construct(
        #[Assert\Uuid]
        public string $userId,
        #[Assert\Email]
        public string $email,
    ) {
    }
}