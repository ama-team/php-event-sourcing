<?php

namespace AmaTeam\EventSourcing\API\Snapshot;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;

interface SnapshotRepositoryInterface extends BasicSnapshotRepositoryInterface
{
    public function getClosest(IdentifierInterface $id, int $version): ?SnapshotContainerInterface;
}
