<?php

namespace App\ProductionProcess\Domain\ValueObject;

enum Quality: string
{
    case NORMAL = 'normal';

    case GOOD = 'good';
}
