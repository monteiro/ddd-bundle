<?php

declare(strict_types=1);

namespace App\DDDBundle\Domain;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Index(columns: ['published'])]
#[ORM\Table(name: 'event_store')]
class StoredEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $eventName;

    #[ORM\Column(type: 'text')]
    private string $eventBody;

    #[ORM\Column(type: 'string', length: 255)]
    private string $aggregateRootId;

    #[ORM\Column(type: 'string', length: 32)]
    private ?string $userId;

    #[ORM\Column(type: 'boolean')]
    private bool $published;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $occurredOn;

    public function __construct($id, $typeName, $eventBody, $aggregateRootId, ?string $userId)
    {
        $this->id        = $id;
        $this->eventName = $typeName;
        $this->eventBody = $eventBody;
        $this->aggregateRootId = $aggregateRootId;
        $this->userId = $userId;

        $this->occurredOn = new \DateTimeImmutable();

        $this->published = false;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getEventBody(): string
    {
        return $this->eventBody;
    }

    public function getAggregateRootId(): string
    {
        return $this->aggregateRootId;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function markAsPublished(): void
    {
        $this->published = true;
    }
    
    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
