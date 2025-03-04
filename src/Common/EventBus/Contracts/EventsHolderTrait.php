<?php

declare(strict_types=1);

namespace Common\EventBus\Contracts;

trait EventsHolderTrait
{
    /** @var list<object> */
    private array $events = [];

    /** @inheritdoc */
    public function getEvents(): array
    {
        return $this->events;
    }

    /** @inheritdoc */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}