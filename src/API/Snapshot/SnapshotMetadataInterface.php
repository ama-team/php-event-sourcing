<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API\Snapshot;

use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;
use DateTimeInterface;

interface SnapshotMetadataInterface
{
    public function getId(): IdentifierInterface;
    public function getVersion(): int;
    public function getCreatedAt(): DateTimeInterface;
}
