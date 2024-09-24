<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Product;

/**
 * OrderData class represents order data.
 */
final readonly class ProductData
{
    /**
     * Class Constructor.
     *
     * @param int                $id             the ID of the object
     * @param float              $length         the length of the object
     * @param string             $filmType       the type of the film for the object
     * @param \DateTimeInterface $addedAt        the date and time when the object was added
     * @param string|null        $laminationType the type of lamination for the object (optional)
     * @param string|null        $orderNumber    the order number for the object (optional)
     * @param string|null        $cutFile        the cut file for the object (optional)
     * @param string|null        $printFile      the print file for the object (optional)
     * @param bool               $isPacked       whether the object is packed or not
     */
    public function __construct(
        public int $id,
        public float $length,
        public string $filmType,
        public ?string $orderNumber,
        public ?string $cutFile,
        public ?string $printFile,
        public \DateTimeInterface $addedAt,
        public ?string $laminationType = null,
        public bool $isPacked = false,
    ) {
    }
}
