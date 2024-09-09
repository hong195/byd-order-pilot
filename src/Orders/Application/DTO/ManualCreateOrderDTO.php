<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

/**
 * OrderData class represents order data.
 */
final readonly class ManualCreateOrderDTO
{
    /**
     * Constructor for the class.
     *
     * @param string      $customerName          the customer's name
     * @param int         $length                the length of the order
     * @param string      $filmType              the film type
     * @param string|null $laminationType        The type of lamination for the order. (Optional)
     * @param string|null $cutFile               The cut file for the order. (Optional)
     * @param string|null $printFile             The print file for the order. (Optional)
     * @param string|null $customerNotes         Any notes from the customer. (Optional)
     * @param string|null $packagingInstructions The packaging instructions for the order. (Optional)
     */
    public function __construct(
        public string $customerName,
        public int $length,
        public string $filmType,
        public ?string $laminationType = null,
        public ?string $cutFile = null,
        public ?string $printFile = null,
        public ?string $customerNotes = null,
        public ?string $packagingInstructions = null,
    ) {
    }
}
