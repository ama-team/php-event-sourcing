<?php

namespace AmaTeam\EventSourcing\API\Event;

interface EventVersionInterface
{
    public function getType(): string;
    public function getVersion(): int;
}
