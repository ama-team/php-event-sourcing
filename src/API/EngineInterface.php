<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API;

use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Event\EventContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Event\EventInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Event\ListenerInterface;
use DateTimeInterface;

/**
 * This interface describes framework API entrypoint
 */
interface EngineInterface
{
    /**
     * Restores entity using persisted
     *
     * @param IdentifierInterface $id
     * @return EntityContainerInterface
     */
    public function get(IdentifierInterface $id): EntityContainerInterface;

    /**
     * @param IdentifierInterface $id
     * @param int $start
     * @param int $limit
     * @return EventContainerInterface[]
     */
    public function getEvents(
        IdentifierInterface $id,
        $start = 0,
        $limit = -1
    ): array;

    /**
     *
     *
     * @param IdentifierInterface $id
     * @param EventInterface $event
     * @param DateTimeInterface|null $occurredAt
     * @param int $attempts
     * @return bool
     */
    public function save(
        IdentifierInterface $id,
        EventInterface $event,
        DateTimeInterface $occurredAt = null,
        int $attempts = 1
    ): bool;

    public function addListener(ListenerInterface $listener): EngineInterface;
}
