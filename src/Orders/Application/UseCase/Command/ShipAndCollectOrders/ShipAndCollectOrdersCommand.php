<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ShipAndCollectOrders;

use App\Shared\Application\Command\CommandInterface;

/**
 * ShipAndCollectOrdersCommand class.
 *
 * Description: This class represents a command to ship and collect orders.
 * Implements CommandInterface.
 *
 * */
readonly class ShipAndCollectOrdersCommand implements CommandInterface
{
    /**
     * Class Constructor.
     *
     * @param int $id the ID of the object
     */
    public function __construct(public int $id)
    {
    }
}
