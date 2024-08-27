<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

/**
 * OrderData class represents order data.
 */
final readonly class OrderData
{
    /**
     * Class Constructor.
     *
     * @param int                $id             the ID of the object
     * @param string             $status         the status of the object
     * @param bool               $hasPriority    whether the object has priority
     * @param int                $length         the length of the object
     * @param string             $orderType      the order type of the object
     * @param string             $filmType       the film type of the object
     * @param \DateTimeInterface $addedAt        the date and time when the object was added
     * @param string|null        $laminationType the lamination type of the object, defaults to null
     * @param int|null           $sortOrder      the sort order of the object, defaults to null
     * @param int|null           $orderNumber    the order number of the object, defaults to null
     * @param string|null        $cutFile        the cut file of the object, defaults to null
     * @param string|null        $printFile      the print file of the object, defaults to null
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
        public ?int $orderNumber = null,
        public ?string $cutFile = null,
        public ?string $printFile = null
    ) {
    }
}
