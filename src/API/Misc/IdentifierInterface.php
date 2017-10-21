<?php

namespace AmaTeam\EventSourcing\API\Misc;

interface IdentifierInterface
{
    public function getType(): string;
    public function getId(): string;
}
