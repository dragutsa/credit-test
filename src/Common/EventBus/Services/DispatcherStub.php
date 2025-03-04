<?php

declare(strict_types=1);

namespace Common\EventBus\Services;

use Common\EventBus\Contracts\EventsHolder;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

final readonly class DispatcherStub implements EventDispatcherInterface, ListenerProviderInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private SymfonyEventDispatcherInterface $dispatcher,
    ) {
    }

    public function dispatchEventsHolder(EventsHolder $eventsHolder): void
    {
        foreach ($eventsHolder->releaseEvents() as $event) {
            $this->dispatch($event);
        }
    }

    /**
     * @template T of object
     * @param T $event
     * @return T
     */
    public function dispatch(object $event): object
    {
        foreach ($this->getListenersForEvent($event) as $listener) {
            try {
                $listener($event);
            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage());
            }
        }

        try {
            $this->publish($event);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
        }

        return $event;
    }

    private function publish(object $event): void
    {
        //todo: publish event to message broker
    }

    /**
     * @template T of object
     * @param T $event
     * @return iterable<callable>
     */
    public function getListenersForEvent(object $event): iterable
    {
        return $this->dispatcher->getListeners(get_class($event));
    }
}