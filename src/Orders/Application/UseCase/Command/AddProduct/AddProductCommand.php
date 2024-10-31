<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\AddProduct;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to manually add an order.
 */
readonly class AddProductCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param int         $orderId        the order ID
     * @param string|null $filmType       the film type
     * @param string|null $laminationType the lamination type
     * @param int|null    $cutFileId      the cut file ID
     * @param int|null    $printFileId    the print file ID
     */
    public function __construct(
        public int $orderId,
        public float $length,
        public ?string $filmType,
        public ?string $laminationType = null,
        public ?int $cutFileId = null,
        public ?int $printFileId = null,
    ) {
    }
}
