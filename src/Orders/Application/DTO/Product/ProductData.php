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
	 * @param int $id The ID of the object.
	 * @param string $status The status of the object.
	 * @param bool $hasPriority Whether the object has priority or not.
	 * @param float $length The length of the object.
	 * @param string $filmType The type of the film for the object.
	 * @param \DateTimeInterface $addedAt The date and time when the object was added.
	 * @param string|null $laminationType The type of lamination for the object (optional).
	 * @param string|null $orderNumber The order number for the object (optional).
	 * @param string|null $cutFile The cut file for the object (optional).
	 * @param string|null $printFile The print file for the object (optional).
	 * @param bool $isPacked Whether the object is packed or not.
	 */
    public function __construct(
        public int $id,
        public string $status,
        public bool $hasPriority,
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
