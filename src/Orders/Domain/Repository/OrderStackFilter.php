<?php

declare(strict_types=1);

namespace App\Orders\Domain\Repository;

/**
 * Class OrderStackFilter.
 *
 * Filter for ordering stacks
 */
readonly class OrderStackFilter
{
    /**
     * Class constructor.
     *
     * @param string|null $rollType       the roll type
     * @param string|null $laminationType the quality of the roll
     */
    public function __construct(public ?string $rollType = null, public ?string $laminationType = null)
    {
    }
}
