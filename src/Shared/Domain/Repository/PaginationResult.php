<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

/**
 * Represents the result of a pagination.
 */
readonly class PaginationResult
{
    /**
     * Constructor for the class.
     *
     * @param array<object> $items the list of items
     * @param int           $total the total count of items
     */
    public function __construct(public array $items, public int $total)
    {
    }
}
