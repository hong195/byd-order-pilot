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
     * @param int                $id                    the order ID
     * @param string             $customerName          the name of the customer who placed the order
     * @param string             $status                the status of the order
     * @param bool               $hasPriority           indicates whether the order has priority or not
     * @param int                $length                the length of the order
     * @param string             $orderType             the type of the order
     * @param string             $filmType              the type of film used in the order
     * @param \DateTimeInterface $addedAt               the date and time when the order was added
     * @param string|null        $laminationType        the type of lamination used in the order (optional)
     * @param int|null           $sortOrder             the sort order of the order (optional)
     * @param string|null        $orderNumber           the order number (optional)
     * @param string|null        $cutFile               the file path of the cut file (optional)
     * @param string|null        $printFile             the file path of the print file (optional)
     * @param string|null        $customerNotes         additional notes provided by the customer (optional)
     * @param string|null        $packagingInstructions packaging instructions (optional)
     * @param bool               $isPacked              indicates whether the order is packed or not
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
        public ?string $packagingInstructions = null,
        public bool $isPacked = false,
    ) {
    }
}
