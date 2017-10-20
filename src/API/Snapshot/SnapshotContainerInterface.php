<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API\Snapshot;

use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityInterface;

interface SnapshotContainerInterface
{
    public function getEntity(): ?EntityInterface;
    public function getMetadata(): SnapshotMetadataInterface;
}
