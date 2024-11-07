<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CheckInPrintedProducts;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to change the priority of an order.
 */
readonly class CheckInPrintedProductsCommand implements CommandInterface
{
    /**
     * Constructor for the class.
     *
     * @param int[] $printedProductIds an array of printed product IDs
     */
    public function __construct(public array $printedProductIds = [])
    {
    }
}
