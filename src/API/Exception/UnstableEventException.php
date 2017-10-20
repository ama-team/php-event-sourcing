<?php

namespace AmaTeam\Bundle\EventSourcingBundle\API\Exception;

use AmaTeam\Bundle\EventSourcingBundle\API\ExceptionInterface;
use RuntimeException;

class UnstableEventException extends RuntimeException implements
    ExceptionInterface
{

}
