<?php

namespace App\Inventory\Domain\Aggregate;

/**
 * @enum FilmType: string
 * {
 *     case ROLL = 'roll';
 *     case LAMINATION = 'lamination';
 * }
 */
enum FilmType: string
{
    case ROLL = 'roll';

    case LAMINATION = 'lamination';
}
