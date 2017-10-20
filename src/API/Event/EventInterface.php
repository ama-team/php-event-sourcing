<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API\Event;

use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityInterface;

interface EventInterface
{
    public function apply(
        EntityInterface $entity,
        EventMetadataInterface $metadata
    ): EntityInterface;

    /**
     * @param EntityInterface $entity
     * @param EventMetadataInterface $metadata
     * @return string[] List of violations in free form
     */
    public function validate(
        EntityInterface $entity,
        EventMetadataInterface $metadata
    ): array;
}
