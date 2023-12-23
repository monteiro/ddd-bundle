<?php

declare(strict_types=1);

namespace App\DDDBundle\Domain;

interface DomainEvent
{
    public function getAggregateRootId(): string;

    public function getUserId(): ?string;

    public function getOccurredOn(): \DateTimeImmutable;
}
