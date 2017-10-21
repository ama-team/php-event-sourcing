<?php

namespace AmaTeam\EventSourcing\API\Misc;

interface PurgeReportInterface
{
    public function getEventCount(): ?int;
    public function getSnapshotCount(): ?int;
}
