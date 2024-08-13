<?php

namespace App\Orders\Domain\ValueObject;

/**
 * Enum RollType represents the different types of rolls.
 *
 * */
enum RollType: string
{
    case CHROME = 'chrome';
    case NEON = 'neon';
    case WHITE = 'white';
    case CLEAR = 'clear';
    case ECO = 'eco';
}
