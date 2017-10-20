<?php

namespace AmaTeam\Bundle\EventSourcingBundle\Engine\Event;

use AmaTeam\Bundle\EventSourcingBundle\API\Event\EventMetadataInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;
use DateTimeInterface;

class EventMetadata implements EventMetadataInterface
{
    /**
     * @var int
     */
    private $index;
    /**
     * @var IdentifierInterface
     */
    private $entityId;
    /**
     * @var DateTimeInterface
     */
    private $occurredAt;
    /**
     * @var DateTimeInterface
     */
    private $acknowledgedAt;

    /**
     * @param int $index
     * @param IdentifierInterface $entityId
     * @param DateTimeInterface $occurredAt
     * @param DateTimeInterface $acknowledgedAt
     */
    public function __construct(
        $index,
        IdentifierInterface $entityId,
        DateTimeInterface $acknowledgedAt,
        DateTimeInterface $occurredAt = null
    ) {
        $this->index = $index;
        $this->entityId = $entityId;
        $this->occurredAt = $occurredAt;
        $this->acknowledgedAt = $acknowledgedAt;
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
