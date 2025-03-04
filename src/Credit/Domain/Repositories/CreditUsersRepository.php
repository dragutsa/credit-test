<?php

declare(strict_types=1);

namespace Credit\Domain\Repositories;

use Common\ValueObjects\UserId;
use Credit\Domain\Entities\CreditUser;

interface CreditUsersRepository
{
    public function save(CreditUser $user): CreditUser;

    public function find(UserId $userId): ?CreditUser;

    public function exists(UserId $userId): bool;
}