<?php

namespace App\Orders\Domain\Aggregate\Roll;

enum RollType: string
{
    case NEON = 'neon';
    case CHROME = 'chrome';
    case SHADOW = 'regular';
}
