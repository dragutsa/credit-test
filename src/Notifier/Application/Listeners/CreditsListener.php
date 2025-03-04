<?php

declare(strict_types=1);

namespace Notifier\Application\Listeners;

use Credit\Domain\Events\CreditAdded;
use Notifier\Domain\Repositories\NotifierUsersRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final readonly class CreditsListener
{
    public function __construct(
        private NotifierUsersRepository $notifierUsersRepository,
    ) {
    }

    #[AsEventListener(event: CreditAdded::class)]
    public function onCreditAdded(CreditAdded $event): void
    {
        $user = $this->notifierUsersRepository->find($event->userId) ?? throw new \RuntimeException('User not found');
        //todo: send email or sms to user
    }
}