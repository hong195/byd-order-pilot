<?php

namespace App\ProductionProcess\Domain\ValueObject;

/**
 * @enum OrderType
 */
enum OrderType: string
{
    case Product = 'product';

    case Extra = 'extra';
    case Combined = 'combined';
}
