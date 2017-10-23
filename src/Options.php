<?php

namespace AmaTeam\EventSourcing;

class Options
{
    /**
     * @var int
     */
    private $snapshotInterval = 50;
    /**
     * @var int
     */
    private $snapshotAmount = 5;
    /**
     * @var bool
     */
    private $directMappingAllowed = false;

    /**
     * @return int
     */
    public function getSnapshotInterval(): int
    {
        return $this->snapshotInterval;
    }

    /**
     * @param int $snapshotInterval
     * @return $this
     */
    public function setSnapshotInterval(int $snapshotInterval)
    {
        $this->snapshotInterval = $snapshotInterval;
        return $this;
    }

    /**
     * @return int
     */
    public function getSnapshotAmount(): int
    {
        return $this->snapshotAmount;
    }

    /**
     * @param int $snapshotAmount
     * @return $this
     */
    public function setSnapshotAmount(int $snapshotAmount)
    {
        $this->snapshotAmount = $snapshotAmount;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDirectMappingAllowed(): bool
    {
        return $this->directMappingAllowed;
    }

    /**
     * @param bool $directMappingAllowed
     */
    public function setDirectMappingAllowed(bool $directMappingAllowed)
    {
        $this->directMappingAllowed = $directMappingAllowed;
    }
}
