<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API;

interface IdentifierInterface
{
    public function getType(): string;
    public function getId(): string;
}
