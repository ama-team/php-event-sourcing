<?php

namespace AmaTeam\EventSourcing\API\Storage;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use DateTimeInterface;

interface EntryInterface
{
    public function getStream(): IdentifierInterface;
    public function getIndex(): int;
    public function getVersion(): int;
    public function getType(): ?string;
    public function getData(): array;
    public function getOccurredAt(): ?DateTimeInterface;
    public function getAcknowledgedAt(): DateTimeInterface;
    public function getCreatedAt(): ?DateTimeInterface;
}
