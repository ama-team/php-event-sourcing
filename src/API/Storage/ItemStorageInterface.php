<?php

namespace AmaTeam\EventSourcing\API\Storage;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;

interface ItemStorageInterface
{
    /**
     * @param IdentifierInterface $id
     * @return int
     */
    public function count(IdentifierInterface $id): int;

    /**
     * @param QueryInterface $query
     * @return ItemInterface[]
     */
    public function fetch(QueryInterface $query): array;

    /**
     * Tries to save item with specified index and stream id and
     * returns success status
     *
     * @param ItemInterface $item
     * @return bool
     */
    public function commit(ItemInterface $item): bool;

    /**
     * Deletes stream
     *
     * @param IdentifierInterface $id
     * @return int|null
     */
    public function purge(IdentifierInterface $id): ?int;
}
