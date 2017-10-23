<?php

namespace AmaTeam\EventSourcing\API\Entity;

use AmaTeam\EventSourcing\API\Event\EventDescriptorInterface;

class EntityDescriptor implements EntityDescriptorInterface
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string[]
     */
    private $types;
    /**
     * @var EventDescriptorInterface[]
     */
    private $events;

    /**
     * @param string $className
     * @param string[] $types
     * @param EventDescriptorInterface[] $events
     */
    public function __construct(
        string $className,
        array $types,
        EventDescriptorInterface... $events
    ) {
        $this->className = $className;
        $this->type = $types;
        $this->events = $events;
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }
    /**
     * @return EventDescriptorInterface[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }
}
