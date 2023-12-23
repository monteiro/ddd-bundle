<?php

namespace App\DDDBundle\Tests\Infrastructure\Console;

use App\DDDBundle\Domain\FakeDomainEvent;
use App\DDDBundle\Entity\StoredEvent;
use App\DDDBundle\Infrastructure\Console\PublishDomainEventsCommand;
use App\DDDBundle\Infrastructure\Persistence\InMemoryStoredEventRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\TraceableMessageBus;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class PublishDomainEventsCommandTest extends TestCase
{
    private CommandTester $commandTester;
    private InMemoryStoredEventRepository $storedEventRepository;
    private SerializerInterface|MockObject $serializer;
    private TraceableMessageBus $eventBus;

    protected function setUp(): void
    {
        $this->storedEventRepository = new InMemoryStoredEventRepository();
        $this->eventBus = new TraceableMessageBus(new MessageBus());
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->commandTester = new CommandTester(new PublishDomainEventsCommand(
            $this->storedEventRepository,
            $this->serializer,
            $this->eventBus
        ));
    }

    /**
     * @covers \App\DDDBundle\Infrastructure\Console\PublishDomainEventsCommand
     */
    public function testExecute(): void
    {
        $storedEvent = new StoredEvent(
            Uuid::v7(),
            'SomeEvent',
            '{"foo": "bar"}',
            'some-aggregate-root-id',
            'some-user-id',
        );
        $this->storedEventRepository->append($storedEvent);

        $domainEvent = new FakeDomainEvent('some-aggregate-root-id', 'some-user-id');
        $this->serializer->expects($this->once())->method('deserialize')->willReturn($domainEvent);
        $this->commandTester->execute([
            'batchSize' => 1,
            'limit' => 1,
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('dispatched event "SomeEvent"', $output);
        $dispatchedMessages = $this->eventBus->getDispatchedMessages();
        $this->assertCount(1, $dispatchedMessages);
    }
}
