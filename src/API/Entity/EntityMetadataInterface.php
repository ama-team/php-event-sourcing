<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API\Entity;

use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;
use DateTimeInterface;

interface EntityMetadataInterface
{
    public function getId(): IdentifierInterface;
    public function getVersion(): int;
    public function getUpdatedAt(): ?DateTimeInterface;
    public function getSnapshotVersion(): ?int;
    public function getSnapshotAt(): ?DateTimeInterface;
}
