<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\Product;

/**
 * OrderData class represents order data.
 */
final readonly class ProductData
{
    /**
     * Class Constructor.
     *
     * @param int                $id             the ID of the object
     * @param bool               $hasPriority    whether the object has priority or not
     * @param float              $length         the length of the object
     * @param string             $filmType       the type of the film for the object
     * @param \DateTimeInterface $addedAt        the date and time when the object was added
     * @param string|null        $laminationType the type of lamination for the object (optional)
     * @param string|null        $orderNumber    the order number for the object (optional)
     */
    public function __construct(
        public int $id,
        public bool $hasPriority,
        public float $length,
        public string $filmType,
        public ?string $orderNumber,
        public \DateTimeInterface $addedAt,
        public ?string $laminationType = null,
    ) {
    }
}
