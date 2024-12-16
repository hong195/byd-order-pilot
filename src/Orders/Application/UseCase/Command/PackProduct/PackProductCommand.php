<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\PackProduct;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command for reprinting an order.
 */
readonly class PackProductCommand implements CommandInterface
{
	/**
	 * Class constructor
	 *
	 * @param string $productId The product ID
	 */
    public function __construct(public string $productId)
    {
    }
}
