<?php

namespace AmaTeam\EventSourcing\API\Snapshot;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use DateTimeInterface;

interface SnapshotMetadataInterface
{
    public function getEntityId(): IdentifierInterface;
    public function getNormalizedEntityId(): IdentifierInterface;
    public function getIndex(): int;
    public function getVersion(): int;
    public function getOccurredAt(): ?DateTimeInterface;
    public function getAcknowledgedAt(): DateTimeInterface;
    public function getCreatedAt(): DateTimeInterface;
}
