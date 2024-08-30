<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

/**
 * OrderData class represents order data.
 */
final readonly class OrderData
{
    /**
     * Constructs a new object with the specified parameters.
     *
     * @param int                $id                    the ID of the object
     * @param string             $status                the status of the object
     * @param bool               $hasPriority           whether the object has priority
     * @param int                $length                the length of the object
     * @param string             $orderType             the order type of the object
     * @param string             $filmType              the film type of the object
     * @param \DateTimeInterface $addedAt               the date and time when the object was added
     * @param string|null        $laminationType        The lamination type of the object. Default is null.
     * @param int|null           $sortOrder             The sort order of the object. Default is null.
     * @param string|null        $orderNumber           The order number of the object. Default is null.
     * @param string|null        $cutFile               The cut file of the object. Default is null.
     * @param string|null        $printFile             The print file of the object. Default is null.
     * @param string|null        $customerNotes         The customer notes of the object. Default is null.
     * @param string|null        $packagingInstructions The packaging instructions of the object. Default is null.
     */
    public function __construct(
        public int $id,
        public string $status,
        public bool $hasPriority,
        public int $length,
        public string $orderType,
        public string $filmType,
        public \DateTimeInterface $addedAt,
        public ?string $laminationType = null,
        public ?int $sortOrder = null,
        public ?string $orderNumber = null,
        public ?string $cutFile = null,
        public ?string $printFile = null,
        public ?string $customerNotes = null,
        public ?string $packagingInstructions = null
    ) {
    }
}
