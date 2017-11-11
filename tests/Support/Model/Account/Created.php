<?php

namespace AmaTeam\EventSourcing\Test\Support\Model\Account;

use AmaTeam\EventSourcing\API\Entity\EntityInterface;
use AmaTeam\EventSourcing\API\Event\EventInterface;
use AmaTeam\EventSourcing\API\Event\EventMetadataInterface;
use AmaTeam\EventSourcing\Test\Support\Model\Account;

class Created implements EventInterface
{
    public function apply(?EntityInterface $entity, EventMetadataInterface $metadata): EntityInterface
    {
        return new Account($metadata->getEntityId()->getId());
    }
}
