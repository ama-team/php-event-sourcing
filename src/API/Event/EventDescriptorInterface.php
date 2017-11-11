<?php

namespace AmaTeam\EventSourcing\API\Event;

interface EventDescriptorInterface
{
    /**
     * @return string
     */
    public function getClassName(): string;

    /**
     * @return EventVersionInterface[]
     */
    public function getVersions(): array;
}
