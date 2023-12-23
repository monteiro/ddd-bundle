<?php

declare(strict_types=1);

namespace App\DDDBundle\Application;

use App\DDDBundle\Domain\DomainEvent;
use App\DDDBundle\Domain\DomainEventDispatcherInterface;
use App\DDDBundle\Domain\StoredEventRepository;
use App\DDDBundle\Entity\StoredEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

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
            $this->storedEventRepository->append(
                new StoredEvent(
                    Uuid::v7(),
                    \get_class($domainEvent),
                    $this->serializer->serialize($domainEvent, 'json'),
                    $domainEvent->getAggregateRootId(),
                    $domainEvent->getActorId(),
                )
            );
        }
    }
}
