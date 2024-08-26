<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\PackProduct;

use App\Shared\Application\Command\CommandInterface;

/**
 * Class CheckProductCommand.
 *
 * @implements CommandInterface
 */
readonly class PackProductCommand implements CommandInterface
{
    /**
     * Class construction.
     *
     * @param int  $orderId   the order ID
     * @param int  $productId the product ID
     * @param bool $isPacked  whether the item is checked or not (defaults to false)
     */
    public function __construct(public int $orderId, public int $productId, public bool $isPacked = false)
    {
    }
}
