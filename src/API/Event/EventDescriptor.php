<?php

namespace AmaTeam\EventSourcing\API\Event;

class EventDescriptor implements EventDescriptorInterface
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var EventVersionInterface[]
     */
    private $versions;

    /**
     * @param string $className
     * @param EventVersionInterface[] $versions
     */
    public function __construct(string $className, EventVersionInterface... $versions)
    {
        $this->className = $className;
        $this->versions = $versions;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return EventVersionInterface[]
     */
    public function getVersions(): array
    {
        return $this->versions;
    }
}
