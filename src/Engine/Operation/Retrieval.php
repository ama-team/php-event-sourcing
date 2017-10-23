<?php

namespace AmaTeam\EventSourcing\Engine\Operation;

use AmaTeam\EventSourcing\API\Engine\RegistryInterface;
use AmaTeam\EventSourcing\API\Entity\EntityContainer;
use AmaTeam\EventSourcing\API\Entity\EntityContainerInterface;
use AmaTeam\EventSourcing\API\Entity\EntityMetadata;
use AmaTeam\EventSourcing\API\Event\EventRepositoryInterface;
use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotRepositoryInterface;
use AmaTeam\EventSourcing\API\Storage\Query;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Retrieval implements LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @var EventRepositoryInterface
     */
    private $events;
    /**
     * @var SnapshotRepositoryInterface
     */
    private $snapshots;
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @param EventRepositoryInterface $events
     * @param SnapshotRepositoryInterface $snapshots
     * @param RegistryInterface $registry
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        EventRepositoryInterface $events,
        SnapshotRepositoryInterface $snapshots,
        RegistryInterface $registry,
        LoggerInterface $logger = null
    ) {
        $this->events = $events;
        $this->snapshots = $snapshots;
        $this->registry = $registry;
        $this->logger = $logger ?: new NullLogger();
    }

    public function execute(IdentifierInterface $id, int $version = null)
    {
        $container = $this->restoreFromSnapshot($id, $version);
        $offset = $container->getMetadata()->getVersion();
        $remaining = $this->events->fetch(new Query($id, $offset));
        $operation = new EventApplication();
        foreach ($remaining as $event) {
            $container = $operation->execute($container, $event);
        }
        return $container;
    }

    private function restoreFromSnapshot(IdentifierInterface $id, int $version = null): EntityContainerInterface
    {
        $version = (int) $version;
        $snapshot = $version > 0 ? $this->snapshots->getClosest($id, $version) : null;
        $source = $snapshot ? $snapshot->getMetadata() : null;
        $entity = $snapshot ? $snapshot->getEntity() : null;
        $metadata = new EntityMetadata(
            $id,
            $this->registry->normalizeId($id),
            $source ? $source->getVersion() : 0,
            $source ? $source->getVersion() : 0,
            $source ? $source->getAcknowledgedAt() : null,
            $source ? $source->getOccurredAt() : null
        );
        return new EntityContainer($entity, $metadata);
    }
}
