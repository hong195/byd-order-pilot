<?php

declare(strict_types=1);

namespace App\Rolls\Application\DTO;

/**
 * OrderData class represents order data.
 */
final readonly class OrderData
{
    /**
     * Constructor for the class.
     *
     * @param int                $id             the ID of the object
     * @param string             $status         the status of the object
     * @param string             $priority       the priority of the object
     * @param string             $productType    the product type of the object
     * @param string             $rollType       the roll type of the object
     * @param \DateTimeImmutable $addedAt        the added date of the object
     * @param string|null        $laminationType the lamination type of the object (optional)
     * @param int|null           $orderNumber    the order number of the object (optional)
     * @param string|null        $cutFile        the cut file of the object (optional)
     * @param string|null        $printFile      the print file of the object (optional)
     */
    public function __construct(
        public int $id,
        public string $status,
        public string $priority,
        public int $length,
        public string $productType,
        public string $rollType,
        public \DateTimeInterface $addedAt,
        public ?string $laminationType = null,
        public ?int $orderNumber = null,
        public ?string $cutFile = null,
        public ?string $printFile = null
    ) {
    }
}
