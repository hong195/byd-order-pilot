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
     * @param string      $priority       the priority of the product
     * @param string      $productType    the type of the product
     * @param int         $length         the length of the product
     * @param string|null $laminationType the lamination type of the product (optional)
     * @param string|null $rollType       the roll type of the product (optional)
     * @param string|null $orderNumber    the order number of the product (optional)
     * @param int|null    $cutFileId      the cut file ID of the product (optional)
     * @param int|null    $printFileId    the print file ID of the product (optional)
     *
     * @return void
     */
    public function __construct(
        public string $priority,
        public string $productType,
        public int $length,
        public ?string $laminationType = null,
        public ?string $rollType = null,
        public ?string $orderNumber = null,
        public ?int $cutFileId = null,
        public ?int $printFileId = null,
    ) {
    }
}
