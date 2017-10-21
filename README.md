# AmaTeam\EventSourcing

[![Packagist](https://img.shields.io/packagist/v/ama-team/event-sourcing.svg?style=flat-square)](https://packagist.org/packages/ama-team/event-sourcing)
[![CircleCI/master](https://img.shields.io/circleci/project/github/ama-team/php-event-sourcing/master.svg?style=flat-square)](https://circleci.com/gh/ama-team/php-event-sourcing/tree/master)
[![Coveralls/master](https://img.shields.io/coveralls/ama-team/php-event-sourcing/master.svg?style=flat-square)](https://coveralls.io/github/ama-team/php-event-sourcing?branch=master)
[![Scrutinizer/master](https://img.shields.io/scrutinizer/g/ama-team/php-event-sourcing/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/ama-team/php-event-sourcing/?branch=master)
[![Code Climate](https://img.shields.io/codeclimate/github/ama-team/php-event-sourcing.svg?style=flat-square)](https://codeclimate.com/github/ama-team/php-event-sourcing)

This is outrageously simple event sourcing implementation. If you're
not familiar with concept, try starting with 
[classic Fowler article][bliki] and [this presentation][presentation].

## Installation

The usual composer procedure

```bash
composer require ama-team/event-sourcing
```

The framework stands on PHP 7.1+, Psr/Log and Symfony/Validator.

## Usage

### Conventions

The basis is the very same as always: there are aggregate roots that
are changed in respond to incoming events, events are stored in event
repository, events are listened for, and every N events engine 
triggers a snapshot. But in contrast to many other popular frameworks, 
this one moves focus and responsibility from aggregate root to events. 
Every event is a self-contained piece of logic with required data 
attached, and event is passed to engine rather than applied on 
aggregate root directly. Engine takes care of fetching the most
recent version of aggregate root and applying event over it, and,
if everything went smoothly, returns aggregate root just with that
event applied over (returning null otherwise).

As other frameworks, this one allows pluggable backends, so how 
events/snapshots are really stored depends on that backend.

### Workflow

First of all, end user will need an instance of `EngineInterface`.
This could be done with the help of `Builder`:

```php
// The absolute minimum you need is event repository,
// you'll need snapshot repository too to enable snapshots
$engine = (new Builder())
  ->withEventRepository($repository)
  ->build();
```

The engine exposes just several methods: `#get()` for entity fetching,
`#commit()` for applying event over entity, and self-explanatory 
`#addListener()`, `#purge()`, `#getEvents()` and `#getSnapshots()`. 
Each entity is described by identifier, which is a pair of entity type 
(currently jsut entity class) and string id (more types and 
configurations may be added in future releases). 

All events have to implement `EventInterface`, which consists of single
`#apply(entity, metadata): entity` method. This is the main point of 
logic processing: event may juggle entity (aggregate root) as it wants,
and the aggregate root doesn't know anything about events: it just 
carries state around.

```php
class AccountCreatedEvent implements EventInterface {
  public function apply(EntityInterface $entity, EventMetadataInterface $metadata): EntityInterface {
    $id = $metadata->getEntityId()->getId();
    return (new Account($id))
      ->setState(Account::STATE_ACTIVE);
  }
}
```

```php
class AccountChargedEvent implements EventInterface {
  private $amount;
  
  public function __construct(int $amount) {
    $this->amount = $amount;
  }
  
  public function apply(EntityInterface $entity, EventMetadataInterface $metadata): EntityInterface {
    return $entity->setAmount($entity->getAmount() - $this->amount);
  }
}
```

Having logic inside events prevents Fat Ugly Single Point Logic Handler
from appearing on the scene, equally spreading logic over event 
classes. This restricts end user from using additional services in 
event application, but this is intended: event should contain all the 
data required to produce a mutation.

Of course, events also can validate current entity state before 
application. To exploit this opportunity, you'll need to install 
Symfony/Validator, implement ValidatingEventInterface for the events 
you need to turn validation on, and return 
ConstraintViolationListInterface from `#validate()` method:

```php
class AccountChargedEvent implements ValidatingEventInterface {
  public function validate(EntityInterface $entity, EventMetadataInterface $metadata): ?ConstraintViolationListInterface
  {
    if ($entity->getState() !== Account::STATE_ACTIVE) {
      return $this->createViolation('state', '');
    }
    return null;
  }
}
```

Since `#commit()` method may denote only two states under current 
signature (returning aggregate root for success and null for failure),
failed validation will trigger a `ValidationFailureException`.

There is no such thing as event bus in this framework, to react on 
freshly-appended events you'll need to add your own listeners using
`engine#addListener()`. Listeners are run after event has been 
persisted, so any thrown exception won't prevent it (but it will be 
propagated).

### Backends

TBD

## FAQ

### There's ton of implementations around, why another one?

Sadly all of them are terribly off the road. Every one either asks to
put all event handlers inside the aggregate root (either forcing end 
developer to use humongous switch constructions or creating tons of 
methods), stores events inside aggregate root (why the hell it's just 
a dry object), accumulates events before commit (while there's no 
guarantee any of them will pass), or everything at the same time. 
All of them use FQCNs to store events and snapshots, which forbids any
major refactoring (this framework does this as well for now, but it's
planned to use some intermediate mapping).

They all just blindly copy same pattern without admitting how broken it 
is.

The events do nothing but change state. They mutate aggregate root in
a totally predictable, deterministic way; the moment of application is
the moment of insertion in database. The logic belongs to the event 
itself, both semantically and because of O(n) size of single method /
single class event handler that would be maintained otherwise. You 
can't just send event down the bus because there is no certainty it
would be applicable to current state.

## Contributing

Feel free to send pull requests for **dev** branch.

### Dev branch state

Here's how things have been going:

[![CircleCI/dev](https://img.shields.io/circleci/project/github/ama-team/php-event-sourcing/dev.svg?style=flat-square)](https://circleci.com/gh/ama-team/php-event-sourcing/tree/dev)
[![Coveralls/dev](https://img.shields.io/coveralls/ama-team/php-event-sourcing/dev.svg?style=flat-square)](https://coveralls.io/github/ama-team/php-event-sourcing?branch=dev)
[![Scrutinizer/dev](https://img.shields.io/scrutinizer/g/ama-team/php-event-sourcing/dev.svg?style=flat-square)](https://scrutinizer-ci.com/g/ama-team/php-event-sourcing/?branch=dev)

## License

MIT license / AMA Team 2017

  [bliki]: https://martinfowler.com/eaaDev/EventSourcing.html
  [presentation]: https://ookami86.github.io/event-sourcing-in-practice/
