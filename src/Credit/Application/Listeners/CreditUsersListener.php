<?php

declare(strict_types=1);

namespace Credit\Application\Listeners;

use Common\Outbox\Services\OutboxStub;
use Credit\Domain\Entities\CreditUser;
use Credit\Domain\Repositories\CreditUsersRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use User\Domain\Events\UserCreated;

final readonly class CreditUsersListener
{
    public function __construct(
        private CreditUsersRepository $usersRepository,
        private OutboxStub $outbox,
    ) {
    }

    #[AsEventListener(event: UserCreated::class)]
    public function onUserCreated(UserCreated $event): void
    {
        $this->outbox->transactional(function () use ($event) {
            if ($this->usersRepository->exists($event->userId)) {
                $this->error('User already exists');
            }
            return $this->usersRepository->save(
                new CreditUser(
                    $event->userId,
                    $event->birthDate,
                    $event->ssn,
                    $event->state,
                    $event->ficoScore,
                    $event->monthlyIncome,
                ),
            );
        });
    }

    private function error(string $msg): void
    {
        throw new \RuntimeException($msg);
    }
}