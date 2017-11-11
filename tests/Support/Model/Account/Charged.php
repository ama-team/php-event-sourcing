<?php

namespace AmaTeam\EventSourcing\Test\Support\Model\Account;

use AmaTeam\EventSourcing\API\Entity\EntityInterface;
use AmaTeam\EventSourcing\API\Event\EventInterface;
use AmaTeam\EventSourcing\API\Event\EventMetadataInterface;
use AmaTeam\EventSourcing\Test\Support\Model\Account;

class Charged implements EventInterface
{
    /**
     * @var float
     */
    private $amount;

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount(float $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param EntityInterface|Account|null $entity
     * @param EventMetadataInterface $metadata
     * @return EntityInterface
     */
    public function apply(?EntityInterface $entity, EventMetadataInterface $metadata): EntityInterface
    {
        return $entity->setBalance($entity->getBalance() - $this->amount);
    }
}
