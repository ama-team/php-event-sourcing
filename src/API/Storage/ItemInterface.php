<?php

namespace AmaTeam\EventSourcing\API\Storage;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use DateTimeInterface;

interface ItemInterface
{
    public function getStream(): IdentifierInterface;
    public function getIndex(): int;
    public function getVersion(): int;
    public function getType(): ?string;
    public function getData(): array;
    public function getMetadata(): array;
    public function getChangedAt(): ?DateTimeInterface;
    public function getRecordedAt(): DateTimeInterface;
}
