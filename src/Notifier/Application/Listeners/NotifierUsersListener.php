<?php

declare(strict_types=1);

namespace Notifier\Application\Listeners;

use Common\Outbox\Services\OutboxStub;
use Notifier\Domain\Entities\NotifierUser;
use Notifier\Domain\Repositories\NotifierUsersRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use User\Domain\Events\UserCreated;
use User\Domain\Events\UserEmailChanged;

final readonly class NotifierUsersListener
{
    public function __construct(
        private NotifierUsersRepository $usersRepository,
        private OutboxStub $outbox,
    ) {
    }

    #[AsEventListener(event: UserCreated::class)]
    public function onUserCreated(UserCreated $event): void
    {
        $this->outbox->transactional(function () use ($event) {
            if ($this->usersRepository->exists($event->userId)) {
                throw new \RuntimeException('User already exists');
            }
            return $this->usersRepository->save(
                new NotifierUser(
                    $event->userId,
                    $event->fullName,
                    $event->email,
                    $event->phone,
                ),
            );
        });
    }

    #[AsEventListener(event: UserCreated::class)]
    public function onUserEmailChanged(UserEmailChanged $event): void
    {
        $this->outbox->transactional(function () use ($event) {
            $user = $this->usersRepository->find($event->userId) ?? throw new \RuntimeException('User not found');
            $user->changeEmail($event->email);

            return $this->usersRepository->save($user);
        });
    }
}