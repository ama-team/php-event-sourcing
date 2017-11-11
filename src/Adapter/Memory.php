<?php

namespace AmaTeam\EventSourcing\Adapter;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use AmaTeam\EventSourcing\API\Storage\EntryInterface;
use AmaTeam\EventSourcing\API\Storage\QueryInterface;
use AmaTeam\EventSourcing\API\Storage\StreamStorageInterface;

class Memory implements StreamStorageInterface
{
    /**
     * @var EntryInterface[][][]
     */
    private $storage = [];

    public function count(IdentifierInterface $id): int
    {
        return sizeof($this->getStream($id));
    }

    public function fetch(QueryInterface $query): array
    {
        $stream = $this->getStream($query->getId());
        if (!$query->useAscendingOrder()) {
            $stream = array_reverse($stream);
        }
        return array_slice($stream, $query->getOffset() ?? 0, $query->getLimit() ?? PHP_INT_MAX);
    }

    public function commit(EntryInterface $item): bool
    {
        $stream = &$this->getStream($item->getStream());
        if (isset($stream[$item->getIndex()])) {
            return false;
        }
        $stream[$item->getIndex()] = $item;
        return true;
    }

    public function purge(IdentifierInterface $id): ?int
    {
        if (!isset($this->storage[$id->getType()])) {
            return 0;
        }
        $stream = $this->storage[$id->getType()][$id->getId()] ?? [];
        unset($this->storage[$id->getType()][$id->getId()]);
        if (empty($this->storage[$id->getType()])) {
            unset($this->storage[$id->getType()]);
        }
        return sizeof($stream);
    }

    private function &getStream(IdentifierInterface $id): array
    {
        if (!isset($this->storage[$id->getType()])) {
            $this->storage[$id->getType()] = [];
        }
        $cursor = &$this->storage[$id->getType()];
        if (!isset($cursor[$id->getId()])) {
            $cursor[$id->getId()] = [];
        }
        return $cursor[$id->getId()];
    }
}
