<?php

declare(strict_types=1);

namespace App\DDDBundle\Domain;

interface DomainEventDispatcherInterface
{
    /**
     * @param array<DomainEvent> $domainEvents
     */
    public function dispatchAll(array $domainEvents): void;
}
