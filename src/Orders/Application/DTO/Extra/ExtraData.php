<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Extra;

/**
 * OrderData class represents order data.
 */
final readonly class ExtraData
{
    /**
     * Class constructor.
     *
     * @param int    $id          the identifier
     * @param string $name        the name
     * @param string $orderNumber the order number
     * @param int    $count       The count. Default is 0.
     * @param bool   $isPacked    Indicates if it is packed. Default is false.
     */
    public function __construct(public int $id, public string $name, public string $orderNumber, public int $count = 0, public bool $isPacked = false)
    {
    }
}
