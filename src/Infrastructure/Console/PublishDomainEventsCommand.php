<?php

declare(strict_types=1);

namespace App\DDDBundle\Infrastructure\Console;

use App\DDDBundle\Domain\StoredEventRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'ddd:domain:events:publish',
    description: 'publish domain events to the transport',
)]
final class PublishDomainEventsCommand extends Command implements SignalableCommandInterface
{
    private const DEFAULT_BATCH_SIZE = 10;
    private const WAIT_SECONDS = 2;

    private StoredEventRepository $storedEventRepository;
    private SerializerInterface $serializer;
    private MessageBusInterface $eventBus;

    private bool $shouldStop = false;

    public function __construct(
        StoredEventRepository $storedEventRepository,
        SerializerInterface $serializer,
        MessageBusInterface $eventBus
    ) {
        parent::__construct();

        $this->storedEventRepository = $storedEventRepository;
        $this->serializer = $serializer;
        $this->eventBus = $eventBus;
    }

    public function getSubscribedSignals(): array
    {
        return [
            SIGINT,
            SIGTERM,
        ];
    }

    public function handleSignal(int $signal, false|int $previousExitCode = 0): false|int
    {
        $this->shouldStop = true;

        return false;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('batchSize', InputArgument::OPTIONAL, 'batch size', self::DEFAULT_BATCH_SIZE)
            ->addArgument('limit', InputArgument::OPTIONAL, 'limit')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $batchSize = $input->getArgument('batchSize');
        $limit = $input->getArgument('limit');

        $numEventsPublished = 0;

        while (true) {
            $output->writeln('Listening to new domain events...');

            if ($this->shouldStop) {
                break;
            }

            if ($numEventsPublished === $limit) {
                $this->shouldStop = true;
                break;
            }

            $storedEvents = $this->storedEventRepository->nextUnpublishEvents($batchSize);
            foreach ($storedEvents as $storedEvent) {
                if ($numEventsPublished === $limit) {
                    $this->shouldStop = true;
                    break;
                }

                $domainEvent = $this->serializer->deserialize(
                    $storedEvent->getEventBody(),
                    $storedEvent->getEventName(),
                    'json'
                );

                $this->eventBus->dispatch($domainEvent);
                ++$numEventsPublished;

                $storedEvent->markAsPublished();
                $this->storedEventRepository->save($storedEvent);

                $output->writeln(sprintf('dispatched event "%s""', $storedEvent->getEventName()));
            }

            sleep(self::WAIT_SECONDS);
        }

        return self::SUCCESS;
    }
}
