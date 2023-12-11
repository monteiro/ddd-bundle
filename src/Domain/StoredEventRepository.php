<?php

namespace App\DDDBundle\Domain;

interface StoredEventRepository
{
    public function append(StoredEvent $storedEvent): void;

    /**
     * @return array<StoredEvent>
     */
    public function nextUnpublishEvents(int $batchSize): array;
    
    public function nextIdentity(): string;
    
    public function save(StoredEvent $storedEvent): void;
}
