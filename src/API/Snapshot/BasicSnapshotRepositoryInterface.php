<?php

namespace AmaTeam\EventSourcing\API\Snapshot;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use AmaTeam\EventSourcing\API\Storage\QueryInterface;

interface BasicSnapshotRepositoryInterface
{
    /**
     * @param QueryInterface $query
     * @return SnapshotContainerInterface[]
     */
    public function fetch(QueryInterface $query): array;

    /**
     * @param SnapshotContainerInterface $snapshot
     * @return bool
     */
    public function commit(SnapshotContainerInterface $snapshot): bool;

    /**
     * @param IdentifierInterface $id
     * @return int
     */
    public function count(IdentifierInterface $id): int;

    /**
     * @param IdentifierInterface $id
     * @return int|null
     */
    public function purge(IdentifierInterface $id): ?int;
}
