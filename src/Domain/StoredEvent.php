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
    private string $typeName;

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
        $this->id = $id;
        $this->typeName = $typeName;
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

    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): self
    {
        $this->typeName = $typeName;

        return $this;
    }

    public function getEventBody(): string
    {
        return $this->eventBody;
    }

    public function setEventBody(string $eventBody): self
    {
        $this->eventBody = $eventBody;

        return $this;
    }

    public function getAggregateRootId(): string
    {
        return $this->aggregateRootId;
    }

    public function setAggregateRootId(string $aggregateRootId): self
    {
        $this->aggregateRootId = $aggregateRootId;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function markAsPublished(): void
    {
        $this->published = true;
    }
}
