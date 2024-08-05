<?php

declare(strict_types=1);

namespace App\Orders\Domain\Repository;

/**
 * RollFilter class is used to filter rolls based on roll type and lamination type.
 */
readonly class RollFilter
{
    /**
     * Class Constructor.
     *
     * @param string|null $rollType The roll type. Default: null.
     */
    public function __construct(public ?string $rollType = null)
    {
    }
}
