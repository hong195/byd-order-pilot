<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Product;

/**
 * OrderData class represents order data.
 */
final readonly class ProductCreateDTO
{
    /**
     * Class Constructor.
     *
     * @param int         $orderId        The order ID
     * @param float       $length         The length of the film
     * @param string      $filmType       The type of film
     * @param string|null $laminationType The type of lamination (optional)
     * @param int|null    $cutFileId      The ID of the cut file (optional)
     * @param int|null    $printFileId    The ID of the print file (optional)
     */
    public function __construct(
        public string $orderId,
        public float $length,
        public string $filmType,
        public ?string $laminationType = null,
		public null|string|int $cutFileId = null,
		public null|string|int $printFileId = null,
    ) {
    }
}
