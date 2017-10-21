<?php

namespace AmaTeam\EventSourcing\Engine\Operation;

use AmaTeam\EventSourcing\API\Entity\EntityContainer;
use AmaTeam\EventSourcing\API\Entity\EntityContainerInterface;
use AmaTeam\EventSourcing\API\Entity\EntityInterface;
use AmaTeam\EventSourcing\API\Entity\EntityMetadata;
use AmaTeam\EventSourcing\API\Event\EventContainerInterface;
use AmaTeam\EventSourcing\API\Event\ValidatingEventInterface;
use AmaTeam\EventSourcing\API\Exception\ValidationFailureException;

class EventApplication
{
    public function execute(EntityContainerInterface $entity, EventContainerInterface $event): EntityContainerInterface
    {
        $this->validate($event, $entity->getEntity());
        $value = $event->getEvent()->apply($entity->getEntity(), $event->getMetadata());
        $source = $event->getMetadata();
        $metadata = EntityMetadata::from($entity->getMetadata())
            ->withVersion($source->getIndex())
            ->withOccurredAt($source->getOccurredAt())
            ->withAcknowledgedAt($source->getAcknowledgedAt());
        return new EntityContainer($value, $metadata);
    }

    private function validate(EventContainerInterface $container, EntityInterface $entity): void
    {
        $event = $container->getEvent();
        if (!($event instanceof ValidatingEventInterface)) {
            return;
        }
        $violations = $event->validate($entity, $container->getMetadata());
        if (!empty($violations)) {
            throw ValidationFailureException::fromEventViolations($container, $violations);
        }
    }
}
