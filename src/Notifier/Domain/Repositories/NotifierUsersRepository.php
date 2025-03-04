<?php

declare(strict_types=1);

namespace Notifier\Domain\Repositories;

use Common\ValueObjects\UserId;
use Notifier\Domain\Entities\NotifierUser;

interface NotifierUsersRepository
{
    public function save(NotifierUser $user): NotifierUser;

    public function find(UserId $userId): ?NotifierUser;

    public function exists(UserId $userId): bool;
}