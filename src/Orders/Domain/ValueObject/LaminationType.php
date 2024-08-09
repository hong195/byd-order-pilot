<?php

namespace App\Orders\Domain\ValueObject;

/**
 * Enum-like class representing the different types of lamination.
 */
enum LaminationType: string
{
    case GOLD_FLAKES = 'gold_flakes';
    case HOLO_FLAKES = 'holo_flakes';
    case MATT = 'matt';
    case GLOSSY = 'glossy';
}
