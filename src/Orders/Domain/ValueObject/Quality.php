<?php

namespace App\Orders\Domain\ValueObject;

enum Quality: string
{
    case NORMAL = 'normal';

    case GOOD = 'good';
}
