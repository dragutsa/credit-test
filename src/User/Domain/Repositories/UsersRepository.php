<?php

declare(strict_types=1);

namespace User\Domain\Repositories;

use Common\ValueObjects\UserId;
use User\Domain\Entities\User;

interface UsersRepository
{
    public function save(User $user): User;

    public function find(UserId $userId): ?User;

    public function exists(UserId $userId): bool;
}