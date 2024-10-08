<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\PrintedProduct;

use App\ProductionProcess\Application\UseCase\Command\CreatePrintedProduct\CreatePrintedProductCommand;

/**
 * OrderData class represents order data.
 */
final readonly class CreatedPrintedProductData
{
    /**
     * Class constructor.
     *
     * @param int         $productId      the ID of the product
     * @param float       $length         the length of the film
     * @param string      $filmType       the type of film
     * @param string      $orderNumber    the order number
     * @param string|null $laminationType the type of lamination (optional, default: null)
     */
    public function __construct(
        public int $productId,
        public float $length,
        public string $filmType,
        public string $orderNumber,
        public ?string $laminationType = null,
    ) {
    }

    public static function fromCommand(CreatePrintedProductCommand $command): CreatedPrintedProductData
    {
        return new self(
            productId: $command->productId,
            length: $command->length,
            filmType: $command->filmType,
            orderNumber: $command->orderNumber,
            laminationType: $command->laminationType
        );
    }
}
