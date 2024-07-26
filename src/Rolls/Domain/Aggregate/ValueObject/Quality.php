<?php

namespace App\Rolls\Domain\Aggregate\ValueObject;

enum Quality: string
{
    case NORMAL = 'normal';

    case GOOD = 'good';
}
