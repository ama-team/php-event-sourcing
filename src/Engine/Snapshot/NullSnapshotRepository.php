<?php

namespace AmaTeam\EventSourcing\Engine\Snapshot;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotContainerInterface;
use AmaTeam\EventSourcing\API\Snapshot\BasicSnapshotRepositoryInterface;
use AmaTeam\EventSourcing\API\Storage\QueryInterface;

class NullSnapshotRepository implements BasicSnapshotRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function fetch(QueryInterface $query): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function commit(SnapshotContainerInterface $snapshot): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function count(IdentifierInterface $id): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function purge(IdentifierInterface $id): ?int
    {
        return null;
    }
}
