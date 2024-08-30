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
     * Constructor for the class.
     *
     * @param int         $length                the length of the film
     * @param string|null $filmType              the type of film (nullable)
     * @param bool        $hasPriority           whether or not the film has priority (default false)
     * @param string|null $laminationType        the type of lamination (nullable)
     * @param string|null $orderNumber           the order number (nullable)
     * @param int|null    $cutFileId             the ID of the cut file (nullable)
     * @param int|null    $printFileId           the ID of the print file (nullable)
     * @param string|null $customerNotes         the customer notes (nullable)
     * @param string|null $packagingInstructions the packaging instructions (nullable)
     */
    public function __construct(
        public int $length,
        public ?string $filmType,
        public bool $hasPriority = false,
        public ?string $laminationType = null,
        public ?string $orderNumber = null,
        public ?int $cutFileId = null,
        public ?int $printFileId = null,
        public ?string $customerNotes = null,
        public ?string $packagingInstructions = null
    ) {
    }
}
