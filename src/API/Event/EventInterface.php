<?php

namespace AmaTeam\EventSourcing\API\Event;

use AmaTeam\EventSourcing\API\Entity\EntityInterface;

interface EventInterface
{
    public function apply(?EntityInterface $entity, EventMetadataInterface $metadata): EntityInterface;
}
