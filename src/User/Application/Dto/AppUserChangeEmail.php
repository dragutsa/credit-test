<?php

declare(strict_types=1);

namespace User\Application\Dto;

use Common\ValueObjects\Email;
use Common\ValueObjects\UserId;

final readonly class AppUserChangeEmail
{
    public function __construct(
        public UserId $userId,
        public Email $email,
    ) {
    }
}