<?php

declare(strict_types=1);

namespace App\DDDBundle\Domain;

interface DomainEvent
{
    public function getAggregateRootId(): string;

    public function getActorId(): ?string;

    public function getOccurredOn(): \DateTimeImmutable;
}
