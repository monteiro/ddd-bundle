<?php

declare(strict_types=1);

namespace App\DDDBundle\Domain;

use App\DDDBundle\Entity\StoredEvent;

interface StoredEventRepository
{
    public function append(StoredEvent $storedEvent): void;

    /**
     * @return array<StoredEvent>
     */
    public function nextUnpublishEvents(int $batchSize): array;

    public function save(StoredEvent $storedEvent): void;
}
