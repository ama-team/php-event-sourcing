<?php

namespace AmaTeam\EventSourcing\API\Storage;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;

class Query implements QueryInterface
{
    /**
     * @var IdentifierInterface
     */
    private $stream;
    /**
     * @var int
     */
    private $offset = 0;
    /**
     * @var int|null
     */
    private $limit = null;
    /**
     * @var bool
     */
    private $ascending = true;

    /**
     * @param IdentifierInterface $stream
     * @param int $offset
     * @param int|null $limit
     * @param bool $ascending
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(
        IdentifierInterface $stream,
        $offset = 0,
        $limit = null,
        $ascending = true
    ) {
        $this->stream = $stream;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->ascending = $ascending;
    }

    /**
     * @return IdentifierInterface
     */
    public function getId(): IdentifierInterface
    {
        return $this->stream;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @return bool
     */
    public function useAscendingOrder(): bool
    {
        return $this->ascending;
    }
}
