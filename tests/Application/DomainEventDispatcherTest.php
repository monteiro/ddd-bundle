<?php

namespace App\DDDBundle\Tests\Application;

use App\DDDBundle\Application\DomainEventDispatcher;
use App\DDDBundle\Domain\FakeDomainEvent;
use App\DDDBundle\Entity\StoredEvent;
use App\DDDBundle\Infrastructure\Persistence\InMemoryStoredEventRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

class DomainEventDispatcherTest extends TestCase
{
    private InMemoryStoredEventRepository $storedEventRepository;
    private SerializerInterface|MockObject $serializer;
    private DomainEventDispatcher $domainEventDispatcher;

    protected function setUp(): void
    {
        $this->storedEventRepository = new InMemoryStoredEventRepository();
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->domainEventDispatcher = new DomainEventDispatcher(
            $this->storedEventRepository,
            $this->serializer
        );
    }

    /**
     * @covers \App\DDDBundle\Application\DomainEventDispatcher::dispatchAll
     */
    public function testDispatchAllUsingBatchSize(): void
    {
        $event1 = new FakeDomainEvent('eb6aa140-f23f-452f-aeb8-22bab9c72649', 'd491b753-613b-4ce3-aa96-267fcbb0e669');
        $event2 = new FakeDomainEvent('cc6a5968-254f-4141-8a31-ef057544f708', 'd491b753-613b-4ce3-aa96-267fcbb0e669');

        $domainEvents = [$event1, $event2];

        $this->domainEventDispatcher->dispatchAll($domainEvents);

        $storedEvents = $this->storedEventRepository->nextUnpublishEvents(1);
        $this->assertCount(1, $storedEvents);
        $this->assertInstanceOf(StoredEvent::class, $storedEvents[0]);
        $this->assertEquals($event1->getAggregateRootId(), $storedEvents[0]->getAggregateRootId());
        $this->assertEquals($event1->getUserId(), $storedEvents[0]->getUserId());
    }

    /**
     * @covers \App\DDDBundle\Application\DomainEventDispatcher::dispatchAll
     */
    public function testDispatchAll(): void
    {
        $event1 = new FakeDomainEvent(
            'eb6aa140-f23f-452f-aeb8-22bab9c72649',
            'd491b753-613b-4ce3-aa96-267fcbb0e669'
        );
        $event2 = new FakeDomainEvent(
            'cc6a5968-254f-4141-8a31-ef057544f708',
            'd491b753-613b-4ce3-aa96-267fcbb0e669'
        );

        $domainEvents = [$event1, $event2];

        $this->domainEventDispatcher->dispatchAll($domainEvents);

        $storedEvents = $this->storedEventRepository->nextUnpublishEvents(2);

        $this->assertCount(2, $storedEvents);
        $this->assertInstanceOf(StoredEvent::class, $storedEvents[0]);
        $this->assertEquals($event1->getAggregateRootId(), $storedEvents[0]->getAggregateRootId());
        $this->assertEquals($event1->getUserId(), $storedEvents[0]->getUserId());

        $this->assertInstanceOf(StoredEvent::class, $storedEvents[1]);
        $this->assertEquals($event2->getAggregateRootId(), $storedEvents[1]->getAggregateRootId());
        $this->assertEquals($event2->getUserId(), $storedEvents[1]->getUserId());
    }
}
