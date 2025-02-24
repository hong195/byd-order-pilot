<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ReprintPrintedProduct;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command for reprinting an order.
 */
readonly class ReprintPrintedProductCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param string $printedProductId the order ID
     */
    public function __construct(public string $printedProductId, public string $process, public ?string $reason = null)
    {
    }
}
