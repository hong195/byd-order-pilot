<?php

declare(strict_types=1);

namespace App\Orders\Domain\Repository;

final readonly class ProductFilter
{
    /**
     * Class constructor.
     *
     * @param string|null $orderId    The order ID. Defaults to null.
     * @param string[]    $productIds An array of product IDs. Defaults to an empty array.
     */
    public function __construct(public ?string $orderId = null, public array $productIds = [])
    {
    }
}
