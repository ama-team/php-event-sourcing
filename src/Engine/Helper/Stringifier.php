<?php

namespace AmaTeam\EventSourcing\Engine\Helper;

class Stringifier
{
    public static function stringify($item): string
    {
        if (!is_object($item)) {
            return (string) $item;
        }
        if (method_exists($item, '__toString')) {
            return $item->__toString();
        }
        return get_class($item) . '#' . spl_object_hash($item);
    }
}
