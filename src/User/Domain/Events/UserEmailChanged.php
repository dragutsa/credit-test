<?php

declare(strict_types=1);

namespace User\Domain\Events;

use Common\ValueObjects\Email;
use Common\ValueObjects\UserId;

/** @see \Credit\Application\Listeners\CreditUsersListener::onUserCreated */
final readonly class UserEmailChanged
{
    public function __construct(
        public UserId $userId,
        public Email $email,
    ) {
    }
}