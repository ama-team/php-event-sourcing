<?php

namespace AmaTeam\EventSourcing\API\Event;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use AmaTeam\EventSourcing\API\Storage\QueryInterface;

interface EventRepositoryInterface
{
    /**
     * @param QueryInterface $query
     * @return EventContainerInterface[]
     */
    public function fetch(QueryInterface $query): array;

    /**
     * @param IdentifierInterface $id
     * @return int
     */
    public function count(IdentifierInterface $id): int;

    /**
     * @param EventContainerInterface $event
     * @return bool
     */
    public function commit(EventContainerInterface $event): bool;

    /**
     * @param IdentifierInterface $id
     * @return int|null
     */
    public function purge(IdentifierInterface $id): ?int;
}
