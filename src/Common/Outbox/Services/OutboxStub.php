<?php

declare(strict_types=1);

namespace Common\Outbox\Services;

use Common\EventBus\Contracts\EventsHolder;
use Common\EventBus\Services\DispatcherStub;
use Doctrine\ORM\EntityManagerInterface;

/** Transaction Outbox pattern */
final readonly class OutboxStub
{
    public function __construct(
        private EntityManagerInterface $em,
        private DispatcherStub $dispatcher,
    ) {
    }

    public function transactional(callable $func): object
    {
        $holder = $this->em->wrapInTransaction(function () use ($func) {
            $holder = $func();
            if ($holder instanceof EventsHolder) {
                $events = $holder->getEvents();
                //todo: save $events to outbox
            }
            return $holder;
        });

        if ($holder instanceof EventsHolder) {
            $this->dispatcher->dispatchEventsHolder($holder);
        }

        return $holder;
    }
}