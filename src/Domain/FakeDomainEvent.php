<?php

namespace App\DDDBundle\Domain;

final class FakeDomainEvent implements DomainEvent
{
    private string $id;
    private string $userId;

    private \DateTimeImmutable $occurredOn;

    public function __construct(string $id, string $userId)
    {
        $this->id = $id;
        $this->userId = $userId;

        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getAggregateRootId(): string
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
