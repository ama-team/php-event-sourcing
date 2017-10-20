<?php

namespace AmaTeam\Bundle\EventSourcingBundle;

class Options
{
    private $snapshotInterval = 50;

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
}
