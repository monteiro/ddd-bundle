<?php

namespace App\DDDBundle\Application;

use App\DDDBundle\Domain\DomainEvent;
use App\DDDBundle\Domain\DomainEventDispatcherInterface;
use App\DDDBundle\Domain\StoredEvent;
use App\DDDBundle\Domain\StoredEventRepository;
use Symfony\Component\Serializer\SerializerInterface;

final class DomainEventDispatcher implements DomainEventDispatcherInterface
{
    private StoredEventRepository $storedEventRepository;
    private SerializerInterface $serializer;

    public function __construct(
        StoredEventRepository $storedEventRepository,
        SerializerInterface $serializer
    ) {
        $this->storedEventRepository = $storedEventRepository;
        $this->serializer = $serializer;
    }

    /**
     * @param array<DomainEvent> $domainEvents
     */
    public function dispatchAll(array $domainEvents): void
    {
        foreach ($domainEvents as $domainEvent) {
            $this->storedEventRepository->append(new StoredEvent(
                $this->storedEventRepository->nextIdentity(),
                get_class($domainEvent),
                $this->serializer->serialize($domainEvent, 'json'),
                $domainEvent->getAggregateRootId(),
                $domainEvent->getActorId(),
                )
            );
        }
    }
}
