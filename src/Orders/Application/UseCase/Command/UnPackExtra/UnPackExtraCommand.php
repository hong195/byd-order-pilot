<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UnPackExtra;

use App\Shared\Application\Command\CommandInterface;

final class UnPackExtraCommand implements CommandInterface
{
    public bool $isPacked = false;

    /**
     * Constructs a new instance of the class.
     *
     * @param string $orderId the order ID
     * @param string $extraId the extra ID
     */
    public function __construct(public readonly string $orderId, public readonly string $extraId)
    {
    }
}
