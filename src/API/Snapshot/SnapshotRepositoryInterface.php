<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API\Snapshot;

use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;

interface SnapshotRepositoryInterface
{
    public function get(IdentifierInterface $id): ?SnapshotContainerInterface;
    public function save(
        EntityContainerInterface $entity
    ): SnapshotContainerInterface;
}
