<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CheckRemainingProducts;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command for reprinting an order.
 */
readonly class CheckRemainingProductsCommand implements CommandInterface
{
    /**
     * Constructor.
     */
    public function __construct(public string $rollId)
    {
    }
}
