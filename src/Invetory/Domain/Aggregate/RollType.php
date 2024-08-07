<?php

namespace App\Invetory\Domain\Aggregate;

/**
 * @enum RollType: string
 * {
 *     case ROLL = 'roll';
 *     case LAMINATION = 'lamination';
 * }
 */
enum RollType: string
{
    case ROLL = 'roll';

    case LAMINATION = 'lamination';
}
