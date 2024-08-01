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
     * @param string      $productType    the type of the product
     * @param int         $length         the length of the product
     * @param string|null $rollType       the type of the roll (nullable)
     * @param bool        $hasPriority    indicates if the product has priority (default is false)
     * @param string|null $laminationType the type of the lamination (nullable)
     * @param string|null $orderNumber    the order number (nullable)
     * @param int|null    $cutFileId      the ID of the cut file (nullable)
     * @param int|null    $printFileId    the ID of the print file (nullable)
     */
    public function __construct(
        public string $productType,
        public int $length,
        public ?string $rollType,
        public bool $hasPriority = false,
        public ?string $laminationType = null,
        public ?string $orderNumber = null,
        public ?int $cutFileId = null,
        public ?int $printFileId = null,
    ) {
    }
}
