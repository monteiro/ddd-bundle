<?php

namespace App\DDDBundle\Infrastructure\Persistence;

use App\DDDBundle\Domain\StoredEventRepository;
use App\DDDBundle\Entity\StoredEvent;

final class InMemoryStoredEventRepository implements StoredEventRepository
{
    /**
     * @var array<string, StoredEvent>
     */
    public array $storedEvents = [];

    public function append(StoredEvent $storedEvent): void
    {
        $this->storedEvents[$storedEvent->getId()->toRfc4122()] = $storedEvent;
    }

    public function nextUnpublishEvents(int $batchSize): array
    {
        $unpublishEvents = [];

        foreach ($this->storedEvents as $storedEvent) {
            if (!$storedEvent->isPublished()) {
                $unpublishEvents[] = $storedEvent;
                if (count($unpublishEvents) === $batchSize) {
                    return $unpublishEvents;
                }
            }
        }

        return $unpublishEvents;
    }

    public function save(StoredEvent $storedEvent): void
    {
        $this->storedEvents[$storedEvent->getId()->toRfc4122()] = $storedEvent;
    }
}
