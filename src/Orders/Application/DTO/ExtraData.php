<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

/**
 * OrderData class represents order data.
 */
final readonly class ExtraData
{
    /**
     * Class constructor.
     *
     * @param int    $id          the ID of the item
     * @param string $name        the name of the item
     * @param string $orderNumber the order number of the item
     */
    public function __construct(public int $id, public string $name, public string $orderNumber, public bool $isPacked = false)
    {
    }
}
