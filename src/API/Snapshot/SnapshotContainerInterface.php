<?php

namespace AmaTeam\EventSourcing\API\Snapshot;

use AmaTeam\EventSourcing\API\Entity\EntityInterface;

interface SnapshotContainerInterface
{
    public function getEntity(): ?EntityInterface;
    public function getMetadata(): SnapshotMetadataInterface;
}
