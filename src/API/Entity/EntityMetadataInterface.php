<?php

namespace AmaTeam\EventSourcing\API\Entity;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use DateTimeInterface;

interface EntityMetadataInterface
{
    public function getId(): IdentifierInterface;
    public function getVersion(): ?int;
    public function getSnapshotVersion(): ?int;
    public function getSnapshotIndex(): ?int;
    public function getOccurredAt(): ?DateTimeInterface;
    public function getAcknowledgedAt(): ?DateTimeInterface;
}
