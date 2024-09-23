<?php

namespace App\ProductionProcess\Domain\ValueObject;

/**
 * Enum filmType represents the different types of rolls.
 *
 * */
enum FilmType: string
{
    case CHROME = 'chrome';
    case NEON = 'neon';
    case WHITE = 'white';
    case CLEAR = 'clear';
    case ECO = 'eco';
}
