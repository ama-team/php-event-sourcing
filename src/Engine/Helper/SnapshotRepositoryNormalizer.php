<?php

namespace AmaTeam\EventSourcing\Engine\Helper;

use AmaTeam\EventSourcing\API\Snapshot\BasicSnapshotRepositoryInterface;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotRepositoryInterface;
use AmaTeam\EventSourcing\Engine\Snapshot\NullSnapshotRepository;
use AmaTeam\EventSourcing\Engine\Snapshot\BasicSnapshotRepositoryWrapper;

class SnapshotRepositoryNormalizer
{
    public static function normalize(BasicSnapshotRepositoryInterface $repository = null): SnapshotRepositoryInterface
    {
        $repository = $repository ?: new NullSnapshotRepository();
        if ($repository instanceof SnapshotRepositoryInterface) {
            return $repository;
        }
        return new BasicSnapshotRepositoryWrapper($repository);
    }
}
