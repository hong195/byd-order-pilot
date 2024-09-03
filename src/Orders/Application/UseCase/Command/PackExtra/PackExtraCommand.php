<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\PackExtra;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to pack an extra for an order.
 *
 * This command takes an order ID and an extra ID as parameters and is responsible for packing
 * the specified extra for the given order.
 */
class PackExtraCommand implements CommandInterface
{
    public bool $isPacked = true;

    /**
     * Constructs a new instance of the class.
     *
     * @param int $orderId the order ID
     * @param int $extraId the extra ID
     */
    public function __construct(public readonly int $orderId, public readonly int $extraId)
    {
    }
}
