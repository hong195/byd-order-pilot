<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

/**
 * OrderData class represents order data.
 */
final readonly class OrderData
{
    /**
     * Class constructor.
     *
     * @param int                $id             the unique identifier for the object
     * @param string             $status         the status of the object
     * @param bool               $hasPriority    indicates if the object has priority
     * @param int                $length         the length of the object
     * @param string             $productType    the type of the product
     * @param string             $filmType       the type of the roll
     * @param \DateTimeInterface $addedAt        the date and time when the object was added
     * @param string|null        $laminationType the type of lamination (optional)
     * @param int|null           $orderNumber    the order number associated with the object (optional)
     * @param string|null        $cutFile        the file for cutting (optional)
     * @param string|null        $printFile      the file for printing (optional)
     */
    public function __construct(
        public int $id,
        public string $status,
        public bool $hasPriority,
        public int $length,
        public string $productType,
        public string $filmType,
        public \DateTimeInterface $addedAt,
        public ?string $laminationType = null,
        public ?int $orderNumber = null,
        public ?string $cutFile = null,
        public ?string $printFile = null
    ) {
    }
}
