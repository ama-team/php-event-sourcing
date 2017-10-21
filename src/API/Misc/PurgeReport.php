<?php

namespace AmaTeam\EventSourcing\API\Misc;

class PurgeReport implements PurgeReportInterface
{
    /**
     * @var int|null
     */
    private $eventCount;
    /**
     * @var int|null
     */
    private $snapshotCount;

    /**
     * @param int|null $eventCount
     * @param int|null $snapshotCount
     */
    public function __construct(int $eventCount = null, int $snapshotCount = null)
    {
        $this->eventCount = $eventCount;
        $this->snapshotCount = $snapshotCount;
    }

    /**
     * @inheritDoc
     */
    public function getEventCount(): ?int
    {
        return $this->eventCount;
    }

    /**
     * @inheritDoc
     */
    public function getSnapshotCount(): ?int
    {
        return $this->snapshotCount;
    }
}
