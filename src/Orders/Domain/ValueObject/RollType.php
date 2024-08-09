<?php

namespace App\Orders\Domain\ValueObject;

/**
 * @enum RollType
 *
 * Represents the possible roll types.
 *
 * @property string CHROME  The chrome roll type.
 * @property string NEON   The neon roll type.
 * @property string WHITE  The white roll type.
 * @property string CLEchAR  The clear roll type.
 * @property string ECO  The eco roll type.
 */
enum RollType: string
{
    case CHROME = 'chrome';
    case NEON = 'neon';
    case WHITE = 'white';
    case CLEAR = 'clear';
    case ECO = 'eco';
}
