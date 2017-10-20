<?php

namespace AmaTeam\Bundle\EventSourcingBundle\Engine\Entity;

use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityMetadataInterface;

class EntityContainer implements EntityContainerInterface
{
    /**
     * @var object|null
     */
    private $entity;
    /**
     * @var EntityMetadataInterface
     */
    private $metadata;

    /**
     * @param EntityInterface|null $entity
     * @param EntityMetadataInterface $metadata
     */
    public function __construct(
        ?EntityInterface $entity,
        EntityMetadataInterface $metadata
    ) {
        $this->entity = $entity;
        $this->metadata = $metadata;
    }

    /**
     * @return EntityInterface|null
     */
    public function getEntity(): ?EntityInterface
    {
        return $this->entity;
    }

    /**
     * @return EntityMetadataInterface
     */
    public function getMetadata(): EntityMetadataInterface
    {
        return $this->metadata;
    }
}
