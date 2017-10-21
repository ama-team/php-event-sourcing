<?php

namespace AmaTeam\EventSourcing\API\Event;

interface EventContainerInterface
{
    public function getEvent(): EventInterface;
    public function getMetadata(): EventMetadataInterface;
}
