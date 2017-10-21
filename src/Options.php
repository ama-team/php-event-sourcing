<?php

namespace AmaTeam\EventSourcing;

class Options
{
    private $snapshotInterval = 50;
    private $snapshotAmount = 5;

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
}
