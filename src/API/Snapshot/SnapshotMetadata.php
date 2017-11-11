<?php

namespace AmaTeam\EventSourcing\API\Snapshot;

use AmaTeam\EventSourcing\API\Entity\EntityMetadataInterface;
use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use DateTimeImmutable;
use DateTimeInterface;

class SnapshotMetadata implements SnapshotMetadataInterface
{
    /**
     * @var IdentifierInterface
     */
    private $entityId;
    /**
     * @var IdentifierInterface
     */
    private $normalizedEntityId;
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
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * @param IdentifierInterface $entityId
     * @param IdentifierInterface $normalizedEntityId
     * @param int $index
     * @param int $version
     * @param DateTimeInterface $createdAt
     * @param DateTimeInterface $acknowledgedAt
     * @param DateTimeInterface|null $occurredAt
     */
    public function __construct(
        IdentifierInterface $entityId,
        IdentifierInterface $normalizedEntityId,
        int $index,
        int $version,
        DateTimeInterface $createdAt,
        DateTimeInterface $acknowledgedAt,
        DateTimeInterface $occurredAt = null
    ) {
        $this->entityId = $entityId;
        $this->normalizedEntityId = $normalizedEntityId;
        $this->index = $index;
        $this->version = $version;
        $this->createdAt = $createdAt;
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
     * @return IdentifierInterface
     */
    public function getNormalizedEntityId(): IdentifierInterface
    {
        return $this->normalizedEntityId;
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

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public static function fromEntityMetadata(EntityMetadataInterface $metadata): SnapshotMetadata
    {
        return new SnapshotMetadata(
            $metadata->getId(),
            $metadata->getNormalizedId(),
            ($metadata->getSnapshotIndex() ?: 0) + 1,
            $metadata->getVersion(),
            new DateTimeImmutable(),
            $metadata->getAcknowledgedAt(),
            $metadata->getOccurredAt()
        );
    }
}
