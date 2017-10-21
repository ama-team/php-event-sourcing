<?php

namespace AmaTeam\EventSourcing\API\Event;

use AmaTeam\EventSourcing\API\Misc\Identifier;

class EventContainer implements EventContainerInterface
{
    const STRING_TEMPLATE = 'Event %s#%d (%s)';
    /**
     * @var EventInterface
     */
    private $event;
    /**
     * @var EventMetadataInterface
     */
    private $metadata;

    /**
     * @param EventInterface $event
     * @param EventMetadataInterface $metadata
     */
    public function __construct(EventInterface $event, EventMetadataInterface $metadata)
    {
        $this->event = $event;
        $this->metadata = $metadata;
    }

    /**
     * @return EventInterface
     */
    public function getEvent(): EventInterface
    {
        return $this->event;
    }

    /**
     * @return EventMetadataInterface
     */
    public function getMetadata(): EventMetadataInterface
    {
        return $this->metadata;
    }

    public function __toString(): string
    {
        return static::asString($this);
    }

    public static function asString(EventContainerInterface $container): string
    {
        return sprintf(
            static::STRING_TEMPLATE,
            Identifier::asString($container->getMetadata()->getEntityId()),
            $container->getMetadata()->getIndex(),
            $container->getMetadata()->getType()
        );
    }
}
