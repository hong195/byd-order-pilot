<?php

namespace App\Rolls\Domain\Aggregate\Roll;

enum Quality: string
{
    case NORMAL = 'normal';

    case GOOD = 'good';
}
