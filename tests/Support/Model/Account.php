<?php

namespace AmaTeam\EventSourcing\Test\Support\Model;

use AmaTeam\EventSourcing\API\Entity\EntityInterface;

class Account implements EntityInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_CLOSED = 'closed';
    const STATUS_SUSPENDED = 'suspended';
    /**
     * @var string
     */
    private $id;
    /**
     * @var float
     */
    private $balance = 0.0;
    /**
     * @var string
     */
    private $status = self::STATUS_ACTIVE;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    public function setBalance(float $balance)
    {
        $this->balance = $balance;
    }
}
