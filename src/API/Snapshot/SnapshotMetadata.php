<?php

namespace AmaTeam\EventSourcing\API\Snapshot;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use DateTimeInterface;

class SnapshotMetadata implements SnapshotMetadataInterface
{
    /**
     * @var IdentifierInterface
     */
    private $entityId;
    /**
     * @var int
     */
    private $index;
    /**
     * @var int
     */
    private $version;
    /**
     * @var DateTimeInterface|null
     */
    private $occurredAt;
    /**
     * @var DateTimeInterface
     */
    private $acknowledgedAt;

    /**
     * @param IdentifierInterface $entityId
     * @param int $index
     * @param int $version
     * @param DateTimeInterface|null $occurredAt
     * @param DateTimeInterface $acknowledgedAt
     */
    public function __construct(
        IdentifierInterface $entityId,
        int $index,
        int $version,
        DateTimeInterface $acknowledgedAt,
        DateTimeInterface $occurredAt = null
    ) {
        $this->entityId = $entityId;
        $this->index = $index;
        $this->version = $version;
        $this->acknowledgedAt = $acknowledgedAt;
        $this->occurredAt = $occurredAt;
    }

    /**
     * @return IdentifierInterface
     */
    public function getEntityId(): IdentifierInterface
    {
        return $this->entityId;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getOccurredAt(): ?DateTimeInterface
    {
        return $this->occurredAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getAcknowledgedAt(): DateTimeInterface
    {
        return $this->acknowledgedAt;
    }
}
