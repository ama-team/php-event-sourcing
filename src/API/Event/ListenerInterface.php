<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API\Event;

use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityContainerInterface;

interface ListenerInterface
{
    /**
     * This method will be called for every persisted event with entity
     * event is already applied on.
     *
     * This may help in creating projections and creating notifications.
     *
     * @param EventContainerInterface $event
     * @param EntityContainerInterface $entity
     */
    public function accept(
        EventContainerInterface $event,
        EntityContainerInterface $entity
    ): void;
}
