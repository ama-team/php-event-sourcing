<?php

namespace AmaTeam\EventSourcing\API\Storage;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;

interface StreamStorageInterface
{
    /**
     * @param IdentifierInterface $id
     * @return int
     */
    public function count(IdentifierInterface $id): int;

    /**
     * @param QueryInterface $query
     * @return EntryInterface[]
     */
    public function fetch(QueryInterface $query): array;

    /**
     * Tries to save item with specified index and stream id and
     * returns success status
     *
     * @param EntryInterface $item
     * @return bool
     */
    public function commit(EntryInterface $item): bool;

    /**
     * Deletes stream
     *
     * @param IdentifierInterface $id
     * @return int|null
     */
    public function purge(IdentifierInterface $id): ?int;
}
