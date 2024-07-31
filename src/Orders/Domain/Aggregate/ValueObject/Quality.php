<?php

namespace App\Orders\Domain\Aggregate\ValueObject;

enum Quality: string
{
    case NORMAL = 'normal';

    case GOOD = 'good';
}
