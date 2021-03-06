<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API\Event;

interface EventContainerInterface
{
    public function getEvent(): EventInterface;
    public function getMetadata(): EventMetadataInterface;
}
