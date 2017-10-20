<?php

namespace AmaTeam\Bundle\EventSourcingBundle\Engine\Event;

use AmaTeam\Bundle\EventSourcingBundle\API\Event\EventContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Event\EventInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Event\EventMetadataInterface;

class EventContainer implements EventContainerInterface
{
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
    public function __construct(
        EventInterface $event,
        EventMetadataInterface $metadata
    ) {
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
}
