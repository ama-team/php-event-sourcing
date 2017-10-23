<?php

namespace AmaTeam\EventSourcing\API\Entity;

use AmaTeam\EventSourcing\API\Event\EventDescriptorInterface;

interface EntityDescriptorInterface
{
    /**
     * @return string
     */
    public function getClassName(): string;

    /**
     * @return string[]
     */
    public function getTypes(): array;

    /**
     * @return EventDescriptorInterface[]
     */
    public function getEvents(): array;

}
