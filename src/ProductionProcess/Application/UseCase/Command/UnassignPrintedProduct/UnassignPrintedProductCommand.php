<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\UnassignPrintedProduct;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to change the priority of an order.
 */
readonly class UnassignPrintedProductCommand implements CommandInterface
{
    /**
     * Constructor.
     *
     * @param string $id the ID of the object
     */
    public function __construct(public string $id)
    {
    }
}
