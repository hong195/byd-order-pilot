<?php

namespace App\Rolls\Domain\Aggregate;

enum Quality: string
{
    case NORMAL = 'normal';

    case GOOD = 'good';
}
