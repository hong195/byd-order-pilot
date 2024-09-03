<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UnPackMainProduct;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command for reprinting an order.
 */
readonly class UnPackMainProductCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param int $orderId the order ID
     */
    public function __construct(public int $rollId, public int $orderId)
    {
    }
}
