<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

/**
 * OrderData class represents order data.
 */
final readonly class OrderData
{
    /**
     * Constructor for the class.
     *
     * @param int                $id                    the order ID
     * @param string             $customerName          the customer's name
     * @param string             $status                the order status
     * @param bool               $hasPriority           whether the order has priority
     * @param int                $length                the length of the order
     * @param string             $orderType             the order type
     * @param string             $filmType              the film type
     * @param \DateTimeInterface $addedAt               the date and time the order was added
     * @param string|null        $laminationType        The type of lamination for the order. (Optional)
     * @param int|null           $sortOrder             The sort order of the order. (Optional)
     * @param string|null        $orderNumber           The order number. (Optional)
     * @param string|null        $cutFile               The cut file for the order. (Optional)
     * @param string|null        $printFile             The print file for the order. (Optional)
     * @param string|null        $customerNotes         Any notes from the customer. (Optional)
     * @param string|null        $packagingInstructions The packaging instructions for the order. (Optional)
     */
    public function __construct(
        public int $id,
        public string $customerName,
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
