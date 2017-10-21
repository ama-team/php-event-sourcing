<?php

namespace AmaTeam\EventSourcing\API;

use AmaTeam\EventSourcing\API\Entity\EntityContainerInterface;
use AmaTeam\EventSourcing\API\Event\EventContainerInterface;
use AmaTeam\EventSourcing\API\Event\EventInterface;
use AmaTeam\EventSourcing\API\Event\ListenerInterface;
use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use AmaTeam\EventSourcing\API\Misc\PurgeReportInterface;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotContainerInterface;
use AmaTeam\EventSourcing\API\Storage\QueryInterface;
use DateTimeInterface;

/**
 * This interface describes framework API entrypoint
 */
interface EngineInterface
{
    /**
     * Retrieves entity using stored snapshots and events
     *
     * @param IdentifierInterface $id
     * @param int|null $version Target entity version.
     * @return EntityContainerInterface
     */
    public function get(IdentifierInterface $id, int $version = null): EntityContainerInterface;

    /**
     * Persists new event
     *
     * @param IdentifierInterface $id
     * @param EventInterface $event
     * @param DateTimeInterface|null $occurredAt
     * @param int $attempts
     * @return EntityContainerInterface|null
     */
    public function commit(
        IdentifierInterface $id,
        EventInterface $event,
        DateTimeInterface $occurredAt = null,
        int $attempts = 1
    ): ?EntityContainerInterface;

    /**
     * @param IdentifierInterface $id
     * @return PurgeReportInterface
     */
    public function purge(IdentifierInterface $id): PurgeReportInterface;

    /**
     * Adds listener for persisted events. Each time event is successfully
     * persisted, listener is called.
     *
     * @param ListenerInterface $listener
     * @return EngineInterface
     */
    public function addListener(ListenerInterface $listener): EngineInterface;

    /**
     * @param QueryInterface $query
     * @return EventContainerInterface[]
     */
    public function getEvents(QueryInterface $query): array;

    /**
     * @param QueryInterface $query
     * @return SnapshotContainerInterface[]
     */
    public function getSnapshots(QueryInterface $query): array;
}
