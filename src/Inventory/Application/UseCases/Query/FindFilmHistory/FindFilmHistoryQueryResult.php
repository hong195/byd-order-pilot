<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\FindFilmHistory;

use App\Inventory\Application\UseCases\Query\DTO\FilmHistoryData;
use App\Shared\Application\Query\QueryInterface;

/**
 * Represents the result of a history query.
 */
final readonly class FindFilmHistoryQueryResult implements QueryInterface
{
    /**
     * Class constructor.
     *
     * @param FilmHistoryData[] $items an array of items
     * @param int               $total the total number of items
     */
    public function __construct(public array $items = [], public int $total = 0)
    {
    }
}
