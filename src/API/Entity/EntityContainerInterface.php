<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API\Entity;

interface EntityContainerInterface
{
    public function getEntity(): ?EntityInterface;
    public function getMetadata(): EntityMetadataInterface;
}
