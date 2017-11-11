<?php

namespace AmaTeam\EventSourcing\API\Event;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use DateTimeInterface;

interface EventMetadataInterface
{
    public function getEntityId(): IdentifierInterface;
    public function getNormalizedEntityId(): IdentifierInterface;
    public function getIndex(): int;
    public function getType(): string;
    public function getVersion(): int;
    public function getClassName(): string;
    public function getOccurredAt(): ?DateTimeInterface;
    public function getAcknowledgedAt(): DateTimeInterface;
}
