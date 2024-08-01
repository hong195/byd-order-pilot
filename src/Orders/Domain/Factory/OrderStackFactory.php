<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Lamination\LaminationType;
use App\Orders\Domain\Aggregate\OrderStack\OrderStack;
use App\Orders\Domain\Aggregate\Roll\RollType;

/**
 * Factory class for creating OrderStack objects.
 */
final readonly class OrderStackFactory
{
    /**
     * Creates a new OrderStack instance.
     *
     * @param string      $name           the name of the order stack
     * @param int         $length         the length of the order stack
     * @param string      $rollType       the type of roll for the order stack
     * @param string|null $laminationType the type of lamination for the order stack (optional)
     *
     * @return OrderStack the created OrderStack instance
     */
    public function make(string $name, int $length, string $rollType, ?string $laminationType): OrderStack
    {
        return new OrderStack(
            name: $name,
            length: $length,
            rollType: RollType::from($rollType),
            laminationType: $laminationType ? LaminationType::from($laminationType) : null,
        );
    }
}
