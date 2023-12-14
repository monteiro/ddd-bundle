<?php

declare(strict_types=1);

namespace App\Resources\config;

use App\DDDBundle\Application\DomainEventDispatcher;
use App\DDDBundle\Domain\DomainEventDispatcherInterface;
use App\DDDBundle\Domain\StoredEventRepository;
use App\DDDBundle\Infrastructure\Console\PublishDomainEventsCommand;
use App\DDDBundle\Infrastructure\Persistence\DoctrineStoredEventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {

    $services = $configurator->services();
    $services->set(StoredEventRepository::class)
        ->class(DoctrineStoredEventRepository::class)
        ->args([
            service(ManagerRegistry::class)
        ]);
    
    $services->set(PublishDomainEventsCommand::class)
        ->args([
            service(StoredEventRepository::class),
            service(SerializerInterface::class),
            service(MessageBusInterface::class),
        ])
        ->autoconfigure();
    
    $services->set(DomainEventDispatcherInterface::class)
        ->class(DomainEventDispatcher::class)
        ->args([
            service(StoredEventRepository::class),
            service(SerializerInterface::class),
        ]);
};
