<?php

namespace App\Orders\Domain\ValueObject;

/**
 * @enum OrderType
 */
enum OrderType
{
    case Product;

    case Extra;
    case Combined;
}
