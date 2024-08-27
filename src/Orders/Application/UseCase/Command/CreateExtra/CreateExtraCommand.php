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
     * Constructor for the class.
     *
     * @param int    $orderId     the ID of the order
     * @param string $name        the name of the order
     * @param string $orderNumber the number of the order
     *
     * @return void
     */
    public function __construct(public int $orderId, public string $name, public string $orderNumber)
    {
    }
}
