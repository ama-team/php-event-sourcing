<?php

namespace AmaTeam\Bundle\EventSourcingBundle\Engine\Action;

use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Event\EventContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Exception\UnstableEventException;
use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Snapshot\SnapshotContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\Engine\Entity\EntityContainer;
use AmaTeam\Bundle\EventSourcingBundle\Engine\Entity\EntityMetadata;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

class MergeAction
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
    }

    public function execute(
        IdentifierInterface $id,
        ?SnapshotContainerInterface $snapshot,
        array $events
    ): EntityContainerInterface {
        $snapshotAt = $snapshot ? $snapshot->getMetadata()->getCreatedAt() : null;
        $version = $snapshot ? $snapshot->getMetadata()->getVersion() : null;
        $metadata = new EntityMetadata(
            $id,
            $version ?? 0,
            $snapshotAt,
            $version,
            $snapshotAt
        );
        $entity = $snapshot ? $snapshot->getEntity() : null;
        $container = new EntityContainer($entity, $metadata);
        return array_reduce($events, [$this, 'processEvent'], $container);
    }

    private function processEvent(
        EntityContainerInterface $entityContainer,
        EventContainerInterface $eventContainer
    ): EntityContainerInterface {
        $entity = $this->applyEvent($entityContainer, $eventContainer);
        $metadata = new EntityMetadata(
            $entityContainer->getMetadata()->getId(),
            $eventContainer->getMetadata()->getIndex(),
            $eventContainer->getMetadata()->getAcknowledgedAt(),
            $entityContainer->getMetadata()->getSnapshotVersion(),
            $entityContainer->getMetadata()->getSnapshotAt()
        );
        return new EntityContainer($entity, $metadata);
    }

    private function applyEvent(
        EntityContainerInterface $entity,
        EventContainerInterface $event
    ) {
        try {
            $metadata = $event->getMetadata();
            return $event->getEvent()->apply($entity->getEntity(), $metadata);
        } catch (Throwable $e) {
            $pattern = 'Event %s has thrown an exception while applying to ' .
                'entity %s:%s with version %d';
            $message = sprintf(
                $pattern,
                get_class($event),
                $entity->getMetadata()->getId()->getType(),
                $entity->getMetadata()->getId()->getId(),
                $entity->getMetadata()->getVersion()
            );
            throw new UnstableEventException($message, 0, $e);
        }
    }
}
