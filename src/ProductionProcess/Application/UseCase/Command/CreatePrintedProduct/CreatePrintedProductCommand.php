<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CreatePrintedProduct;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to manually add an order.
 */
readonly class CreatePrintedProductCommand implements CommandInterface
{
    /**
     * Initializes a new instance of the class.
     *
     * @param int         $productId      the ID of the product
     * @param int|float   $length         the length of the product
     * @param string|null $filmType       the type of film used for the product
     * @param string|null $laminationType The type of lamination used for the product. Defaults to null.
     */
    public function __construct(
        public int $productId,
        public string $orderNumber,
        public int|float $length,
        public ?string $filmType,
        public ?string $laminationType = null,
    ) {
    }
}
