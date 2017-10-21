<?php

namespace AmaTeam\EventSourcing\API\Entity;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
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
     * @var int|null
     */
    private $snapshotVersion;
    /**
     * @var int|null
     */
    private $snapshotIndex;
    /**
     * @var DateTimeInterface|null
     */
    private $acknowledgedAt;
    /**
     * @var DateTimeInterface|null
     */
    private $occurredAt;

    /**
     * @param IdentifierInterface $id
     * @param int|null $version
     * @param int|null $snapshotVersion
     * @param int|null $snapshotIndex
     * @param DateTimeInterface|null $acknowledgedAt
     * @param DateTimeInterface|null $occurredAt
     */
    public function __construct(
        IdentifierInterface $id,
        int $version = null,
        int $snapshotVersion = null,
        int $snapshotIndex = null,
        DateTimeInterface $acknowledgedAt = null,
        DateTimeInterface $occurredAt = null
    ) {
        $this->id = $id;
        $this->version = $version;
        $this->snapshotVersion = $snapshotVersion;
        $this->snapshotIndex = $snapshotIndex;
        $this->acknowledgedAt = $acknowledgedAt;
        $this->occurredAt = $occurredAt;
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
     * @return int|null
     */
    public function getSnapshotVersion(): ?int
    {
        return $this->snapshotVersion;
    }

    /**
     * @return int|null
     */
    public function getSnapshotIndex(): ?int
    {
        return $this->snapshotIndex;
    }

    /**
     * @return DateTimeInterface
     */
    public function getAcknowledgedAt(): ?DateTimeInterface
    {
        return $this->acknowledgedAt;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getOccurredAt() : ?DateTimeInterface
    {
        return $this->occurredAt;
    }

    /**
     * @param IdentifierInterface $id
     * @return EntityMetadata
     */
    protected function setId(IdentifierInterface $id): EntityMetadata
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param int $version
     * @return EntityMetadata
     */
    protected function setVersion(int $version): EntityMetadata
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @param int|null $snapshotVersion
     * @return EntityMetadata
     */
    protected function setSnapshotVersion($snapshotVersion)
    {
        $this->snapshotVersion = $snapshotVersion;
        return $this;
    }

    /**
     * @param int|null $snapshotIndex
     * @return $this
     */
    protected function setSnapshotIndex($snapshotIndex)
    {
        $this->snapshotIndex = $snapshotIndex;
        return $this;
    }

    /**
     * @param DateTimeInterface|null $acknowledgedAt
     * @return EntityMetadata
     */
    protected function setAcknowledgedAt($acknowledgedAt)
    {
        $this->acknowledgedAt = $acknowledgedAt;
        return $this;
    }

    /**
     * @param DateTimeInterface|null $occurredAt
     * @return EntityMetadata
     */
    protected function setOccurredAt($occurredAt)
    {
        $this->occurredAt = $occurredAt;
        return $this;
    }

    public function withVersion(int $version = null): EntityMetadata
    {
        return static::from($this)->setVersion($version);
    }

    public function withOccurredAt(DateTimeInterface $occurredAt = null): EntityMetadata
    {
        return static::from($this)->setOccurredAt($occurredAt);
    }

    public function withAcknowledgedAt(DateTimeInterface $acknowledgedAt = null): EntityMetadata
    {
        return static::from($this)->setAcknowledgedAt($acknowledgedAt);
    }

    public function withSnapshotVersion(int $snapshotVersion = null): EntityMetadata
    {
        return static::from($this)->setSnapshotVersion($snapshotVersion);
    }

    public function withSnapshotIndex(int $snapshotIndex = null): EntityMetadata
    {
        return static::from($this)->setSnapshotIndex($snapshotIndex);
    }

    public static function from(EntityMetadataInterface $metadata): EntityMetadata
    {
        return new EntityMetadata(
            $metadata->getId(),
            $metadata->getVersion(),
            $metadata->getSnapshotVersion(),
            $metadata->getAcknowledgedAt(),
            $metadata->getOccurredAt()
        );
    }
}
