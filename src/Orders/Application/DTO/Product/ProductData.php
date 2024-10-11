<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Product;

use App\Orders\Domain\DTO\ProcessDTO;

/**
 * OrderData class represents order data.
 */
final readonly class ProductData
{
    /**
     * Constructor for initializing a new object.
     *
     * @param int                $id             the ID of the object
     * @param float              $length         the length of the object
     * @param string             $filmType       the type of film used
     * @param string|null        $orderNumber    the order number associated with the object, if any
     * @param string|null        $cutFile        the file for cutting
     * @param string|null        $printFile      the file for printing
     * @param \DateTimeInterface $addedAt        the date and time when it was added
     * @param string|null        $laminationType the type of lamination, default is null
     * @param bool               $isPacked       indicates if the object is packed
     * @param ProcessDTO|null    $process        the process associated with the object, if any
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
        public ?ProcessDTO $process = null,
    ) {
    }
}
