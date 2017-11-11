<?php

namespace AmaTeam\EventSourcing\API\Event;

use AmaTeam\EventSourcing\API\Entity\EntityInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ValidatingEventInterface extends EventInterface
{
    /**
     * @param EntityInterface|null $entity
     * @param EventMetadataInterface $metadata
     * @return ConstraintViolationListInterface|null List of validation vioaltions
     */
    public function validate(
        ?EntityInterface $entity,
        EventMetadataInterface $metadata
    ): ?ConstraintViolationListInterface;
}
