<?php

namespace App\Inventory\Domain\Events;

interface EventHasNameInterface
{
    /**
     * Returns a string representation of the object.
     *
     * @return string the string representation of the object
     */
    public function __toString(): string;
}
