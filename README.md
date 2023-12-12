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

