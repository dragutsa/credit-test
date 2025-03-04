<?php

declare(strict_types=1);

namespace Common\EventBus\Contracts;

interface EventsHolder
{
    /** @return list<object> */
    public function getEvents(): array;

    /** @return list<object> */
    public function releaseEvents(): array;
}