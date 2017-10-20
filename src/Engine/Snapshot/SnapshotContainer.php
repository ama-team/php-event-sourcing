<?php

namespace AmaTeam\Bundle\EventSourcingBundle\Engine\Snapshot;

use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Snapshot\SnapshotContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Snapshot\SnapshotMetadataInterface;

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
    public function __construct(
        ?EntityInterface $entity,
        SnapshotMetadataInterface $metadata
    ) {
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
