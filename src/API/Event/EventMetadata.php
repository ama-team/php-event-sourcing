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
     * @var int
     */
    private $index;
    /**
     * @var string
     */
    private $type;
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
     * @param int $index
     * @param string $type
     * @param DateTimeInterface $occurredAt
     * @param DateTimeInterface $acknowledgedAt
     */
    public function __construct(
        IdentifierInterface $entityId,
        int $index,
        string $type,
        DateTimeInterface $acknowledgedAt,
        DateTimeInterface $occurredAt = null
    ) {
        $this->entityId = $entityId;
        $this->index = $index;
        $this->type = $type;
        $this->occurredAt = $occurredAt;
        $this->acknowledgedAt = $acknowledgedAt;
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
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @return IdentifierInterface
     */
    public function getEntityId(): IdentifierInterface
    {
        return $this->entityId;
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
