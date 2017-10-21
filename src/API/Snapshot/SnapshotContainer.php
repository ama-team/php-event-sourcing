<?php

namespace AmaTeam\EventSourcing\API\Snapshot;

use AmaTeam\EventSourcing\API\Entity\EntityInterface;

class SnapshotContainer implements SnapshotContainerInterface
{
    /**
     * @var EntityInterface|null
     */
    private $entity;
    /**
     * @var SnapshotMetadataInterface
     */
    private $metadata;

    /**
     * @param EntityInterface|null $entity
     * @param SnapshotMetadataInterface $metadata
     */
    public function __construct(?EntityInterface $entity, SnapshotMetadataInterface $metadata)
    {
        $this->entity = $entity;
        $this->metadata = $metadata;
    }

    /**
     * @return EntityInterface|null
     */
    public function getEntity(): ?EntityInterface
    {
        return $this->entity;
    }

    /**
     * @return SnapshotMetadataInterface
     */
    public function getMetadata(): SnapshotMetadataInterface
    {
        return $this->metadata;
    }
}
