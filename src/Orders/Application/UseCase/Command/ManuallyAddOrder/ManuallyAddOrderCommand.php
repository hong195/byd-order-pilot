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
     * @param string|null $filmType              the type of film
     * @param string|null $laminationType        the type of lamination
     * @param string|null $customerNotes         any notes provided by the customer
     * @param string|null $packagingInstructions the packaging instructions
     */
    public function __construct(
        public string $customerName,
        public ?string $filmType,
        public ?string $laminationType = null,
        public ?string $customerNotes = null,
        public ?string $packagingInstructions = null,
    ) {
    }
}
