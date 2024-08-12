<?php

declare(strict_types=1);

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\ValueObject\Status;

/**
 * RollFilter class is used to filter rolls based on roll type and lamination type.
 */
readonly class RollFilter
{
    /**
     * Class constructor.
     *
     * @param Status|null $status  The roll type (optional)
     * @param array<int>  $filmIds The film IDs
     */
    public function __construct(public ?Status $status = null, public array $filmIds = [])
    {
    }
}
