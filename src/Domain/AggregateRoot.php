<?php

declare(strict_types=1);

namespace App\DDDBundle\Domain;

trait AggregateRoot
{
    private array $recordedEvents = [];

    public function record(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;
    }

    public function releaseEvents(): array
    {
        $recordedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $recordedEvents;
    }
}
