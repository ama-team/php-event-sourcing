<?php

namespace AmaTeam\Bundle\EventSourcingBundle\Engine\Snapshot;

use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Snapshot\SnapshotContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Snapshot\SnapshotRepositoryInterface;
use DateTimeImmutable;

class NullSnapshotRepository implements SnapshotRepositoryInterface
{
    public function get(IdentifierInterface $id): ?SnapshotContainerInterface
    {
        return null;
    }

    public function save(
        EntityContainerInterface $entity
    ): SnapshotContainerInterface {
        $metadata = new SnapshotMetadata(
            $entity->getMetadata()->getId(),
            new DateTimeImmutable(),
            $entity->getMetadata()->getVersion()
        );
        return new SnapshotContainer($entity->getEntity(), $metadata);
    }
}
