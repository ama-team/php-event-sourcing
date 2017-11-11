<?php

namespace AmaTeam\EventSourcing\API\Event;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use DateTimeInterface;

class EventMetadata implements EventMetadataInterface
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
     * @var string
     */
    private $type;
    /**
     * @var int
     */
    private $version;
    /**
     * @var string
     */
    private $className;
    /**
     * @var DateTimeInterface
     */
    private $occurredAt;
    /**
     * @var DateTimeInterface
     */
    private $acknowledgedAt;

    /**
     * @param IdentifierInterface $entityId
     * @param IdentifierInterface $normalizedEntityId
     * @param int $index
     * @param string $type
     * @param int $version
     * @param string $className
     * @param DateTimeInterface $acknowledgedAt
     * @param DateTimeInterface $occurredAt
     */
    public function __construct( // NOSONAR
        IdentifierInterface $entityId,
        IdentifierInterface $normalizedEntityId,
        int $index,
        string $type,
        int $version,
        string $className,
        DateTimeInterface $acknowledgedAt,
        DateTimeInterface $occurredAt = null
    ) {
        $this->entityId = $entityId;
        $this->normalizedEntityId = $normalizedEntityId;
        $this->index = $index;
        $this->type = $type;
        $this->version = $version;
        $this->className = $className;
        $this->occurredAt = $occurredAt;
        $this->acknowledgedAt = $acknowledgedAt;
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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @return DateTimeInterface
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
