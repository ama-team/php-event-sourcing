<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API\Event;

use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;

interface EventRepositoryInterface
{
    /**
     * @param IdentifierInterface $id
     * @param int $start
     * @param int|null $limit
     * @return EventContainerInterface[]
     */
    public function getEvents(
        IdentifierInterface $id,
        int $start = 0,
        int $limit = -1
    ): array;

    public function count(IdentifierInterface $id): int;

    /**
     * @param EventContainerInterface $event
     * @return bool
     */
    public function save(EventContainerInterface $event): bool;
}
