<?php

namespace AmaTeam\Bundle\EventSourcingBundle\Engine\Snapshot;

use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Snapshot\SnapshotMetadataInterface;
use DateTimeInterface;

class SnapshotMetadata implements SnapshotMetadataInterface
{
    /**
     * @var IdentifierInterface
     */
    private $entityId;
    /**
     * @var DateTimeInterface
     */
    private $createdAt;
    /**
     * @var int
     */
    private $version;

    /**
     * @param IdentifierInterface $entityId
     * @param DateTimeInterface $createdAt
     * @param int $version
     */
    public function __construct(
        IdentifierInterface $entityId,
        DateTimeInterface $createdAt,
        int $version
    ) {
        $this->entityId = $entityId;
        $this->createdAt = $createdAt;
        $this->version = $version;
    }

    /**
     * @return IdentifierInterface
     */
    public function getId(): IdentifierInterface
    {
        return $this->entityId;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }
}
