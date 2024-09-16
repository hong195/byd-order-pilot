<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ManuallyAddOrder;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to manually add an order.
 */
readonly class ManuallyAddOrderCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param string      $customerName          the name of the customer
     * @param int|float   $length                the length of the object
     * @param string|null $filmType              the type of film
     * @param bool        $hasPriority           indicates if the order has priority
     * @param string|null $laminationType        the type of lamination
     * @param string|null $orderNumber           the order number
     * @param int|null    $cutFileId             the ID of the cut file
     * @param int|null    $printFileId           the ID of the print file
     * @param string|null $customerNotes         any notes provided by the customer
     * @param string|null $packagingInstructions the packaging instructions
     */
    public function __construct(
        public string $customerName,
        public int|float $length,
        public ?string $filmType,
        public bool $hasPriority = false,
        public ?string $laminationType = null,
        public ?string $orderNumber = null,
        public ?int $cutFileId = null,
        public ?int $printFileId = null,
        public ?string $customerNotes = null,
        public ?string $packagingInstructions = null,
    ) {
    }
}
