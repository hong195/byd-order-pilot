<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UnPackMainProduct;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command for reprinting an order.
 */
readonly class UnPackProductCommand implements CommandInterface
{
    /**
     * Constructor for the class.
     *
     * @param string $productId the product ID
     */
    public function __construct(public string $productId)
    {
    }
}
