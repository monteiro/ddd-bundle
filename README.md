# DDD-Bundle


## Introduction

When I started to learn about DDD, I found a lot of information about the theory, but not a lot of examples.
I decided to create this bundle to show how I would implement a DDD application using Symfony. 

It is very easy to be purist and decouple everything, but I think it is important to find the right balance between the power
of the framework, and the decoupling of the application.

What we want to achieve with this bundle?

- How to publish domain events inside your entities (can be doctrine entities or not). We want to use doctrine to avoid having to implement our own hydrators.
- How to consume domain events in a different context (e.g. another microservice or the same service). The mindset
changes when you start on your application using domain events. You want to react to those domain events.
- Have interfaces for domain events that will allow to save them in the database (following the outbox pattern).
- Have a separated repository that will use this bundle to test all the behavior in a realtime application.

## Instalation

```bash
composer require monteiro/ddd-bundle
```

You also need to execute the migrations needed to create the "event_store" table.
The event store table will make sure by using the outbox pattern that all events will be saved in the database in the
same transaction as the entity changes.

```bash
bin/console doctrine:migrations:migrate
```

## Usage

### Publishing domain events

When we save a change in an entity, normally we notify other services or handlers that something happened.
We save the event in the database in an event store table, and then we publish the event to the message bus.

The console command used to publish the domain events to the message bus (e.g. rabbitmq, kafka, doctrine, etc) is:

```bash
bin/console ddd:domain:events:publish
```

### Consuming domain events

In order to consume domain events published by other services, you need to create a handler.
The best component for this will be the messenger component. You can create a handler to be consumed for the domain event.

Example of a messenger.yaml configuration:

```yaml
framework:
    messenger:        
        default_bus: command.bus
        buses:
            command.bus:
                middleware:
                  - doctrine_transaction
            event.bus:
                # the 'allow_no_handlers' middleware allows to have no handler                
                default_middleware: allow_no_handlers
        transports:          
            async: '%env(MESSENGER_TRANSPORT_DSN)%'
            failed: 'doctrine://default?queue_name=failed'
            sync: 'sync://'

        routing:
            App\RentCar\Domain\Model\Car\CarWasCreated: async
            App\RentCar\Domain\Model\Customer\CustomerWasCreated: async
            App\RentCar\Domain\Model\Reservation\ReservationWasCreated: async
            App\RentCar\Domain\Model\Reservation\ReservationWasCancelled: async
```

## Demo project

You can try out the demo project: https://github.com/monteiro/rent-car-ddd
Which uses the bundle and has some common DDD pattern examples.

## Install

In order to install we need to execute the following commands:

```bash
composer require monteiro/ddd-bundle
bin/console make:migration
bin/console doctrine:migrations:migrate
```
We need a new migration because we will create the "event_store" table which will store all the events published by our entities.
