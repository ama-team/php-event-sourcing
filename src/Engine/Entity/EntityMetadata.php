<?php

namespace AmaTeam\Bundle\EventSourcingBundle\Engine\Entity;

use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityMetadataInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;
use DateTimeInterface;

class EntityMetadata implements EntityMetadataInterface
{
    /**
     * @var IdentifierInterface
     */
    private $id;
    /**
     * @var int
     */
    private $version;
    /**
     * @var DateTimeInterface|null
     */
    private $updatedAt;
    /**
     * @var int|null
     */
    private $snapshotVersion;
    /**
     * @var DateTimeInterface|null
     */
    private $snapshotAt;

    /**
     * @param IdentifierInterface $id
     * @param int $version
     * @param DateTimeInterface $updatedAt
     * @param int|null $snapshotVersion
     * @param DateTimeInterface|null $snapshotAt
     */
    public function __construct(
        IdentifierInterface $id,
        int $version,
        DateTimeInterface $updatedAt = null,
        int $snapshotVersion = null,
        DateTimeInterface $snapshotAt = null
    ) {
        $this->id = $id;
        $this->version = $version;
        $this->updatedAt = $updatedAt;
        $this->snapshotVersion = $snapshotVersion;
        $this->snapshotAt = $snapshotAt;
    }

    /**
     * @return IdentifierInterface
     */
    public function getId(): IdentifierInterface
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return int|null
     */
    public function getSnapshotVersion(): ?int
    {
        return $this->snapshotVersion;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getSnapshotAt(): ?DateTimeInterface
    {
        return $this->snapshotAt;
    }
}
