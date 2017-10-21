<?php

namespace AmaTeam\EventSourcing\Engine\Snapshot;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotRepositoryInterface;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotContainerInterface;
use AmaTeam\EventSourcing\API\Snapshot\BasicSnapshotRepositoryInterface;
use AmaTeam\EventSourcing\API\Storage\Query;
use AmaTeam\EventSourcing\API\Storage\QueryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class BasicSnapshotRepositoryWrapper implements SnapshotRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @var BasicSnapshotRepositoryInterface
     */
    private $repository;

    /**
     * @param BasicSnapshotRepositoryInterface $repository
     */
    public function __construct(BasicSnapshotRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->logger = new NullLogger();
    }

    /**
     * @inheritDoc
     */
    public function getClosest(IdentifierInterface $id, int $version): ?SnapshotContainerInterface
    {
        $offset = 0;
        $limit = 10;
        while (true) {
            $slice = $this->repository->fetch(new Query($id, $offset, $limit, false));
            if (empty($slice)) {
                return null;
            }
            foreach ($slice as $snapshot) {
                if ($snapshot->getMetadata()->getVersion() <= $version) {
                    return $snapshot;
                }
            }
            $offset += $limit;
        }
        // calming static analyzer
        return null;
    }

    # region delegation

    /**
     * @inheritDoc
     */
    public function fetch(QueryInterface $query): array
    {
        return $this->repository->fetch($query);
    }

    /**
     * @inheritDoc
     */
    public function commit(SnapshotContainerInterface $snapshot): bool
    {
        return $this->repository->commit($snapshot);
    }

    /**
     * @inheritDoc
     */
    public function count(IdentifierInterface $id): int
    {
        return $this->repository->count($id);
    }

    /**
     * @inheritDoc
     */
    public function purge(IdentifierInterface $id): ?int
    {
        return $this->repository->purge($id);
    }

    # endregion
}
