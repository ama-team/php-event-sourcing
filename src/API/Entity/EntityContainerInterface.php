<?php

namespace AmaTeam\EventSourcing\API\Entity;

interface EntityContainerInterface
{
    public function getEntity(): ?EntityInterface;
    public function getMetadata(): EntityMetadataInterface;
}
