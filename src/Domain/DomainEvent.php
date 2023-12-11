<?php
namespace App\DDDBundle\Domain;

interface DomainEvent
{
    public function getAggregateRootId(): string;
    
    public function getActorId(): ?string;

    public function getOccurredOn(): \DateTimeImmutable;
}
