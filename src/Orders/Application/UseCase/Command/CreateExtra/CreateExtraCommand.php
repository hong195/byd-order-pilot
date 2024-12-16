<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\CreateExtra;

use App\Shared\Application\Command\CommandInterface;

/**
 * CreateExtraCommand class.
 *
 * Represents a command to create an extra in the application.
 */
readonly class CreateExtraCommand implements CommandInterface
{
    /**
     * Represents a constructor for a class.
     *
     * @param string $orderId     the ID of the order
     * @param string $name        the name of the order
     * @param string $orderNumber the number of the order
     * @param int    $count       the count of the items in the order
     */
    public function __construct(public string $orderId, public string $name, public string $orderNumber, public int $count)
    {
    }
}
