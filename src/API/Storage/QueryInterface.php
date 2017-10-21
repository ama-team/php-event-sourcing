<?php

namespace AmaTeam\EventSourcing\API\Storage;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;

interface QueryInterface
{
    public function getId(): IdentifierInterface;
    public function getOffset(): ?int;
    public function getLimit(): ?int;
    public function useAscendingOrder(): bool;
}
