<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API\Event;

use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;
use DateTimeInterface;

interface EventMetadataInterface
{
    public function getIndex(): int;
    public function getEntityId(): IdentifierInterface;
    public function getOccurredAt(): ?DateTimeInterface;
    public function getAcknowledgedAt(): DateTimeInterface;
}
