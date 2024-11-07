<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\DTO;

use App\Inventory\Domain\Aggregate\History;

/**
 * Class FilmHistoryDataTransformer.
 */
final readonly class FilmHistoryDataTransformer
{
    /**
     * Transforms an iterable list of items from history into an array of data.
     *
     * @param iterable<History> $list the iterable list of items from history
     *
     * @return FilmHistoryData[] the array of transformed data
     */
    public function fromHistoryList(iterable $list): array
    {
        $data = [];
        foreach ($list as $item) {
            $data[] = $this->fromHistory($item);
        }

        return $data;
    }

    /**
     * Converts a History object to a FilmHistoryData object.
     *
     * @param History $history The History object to convert
     *
     * @return FilmHistoryData The converted FilmHistoryData object
     */
    public function fromHistory(History $history): FilmHistoryData
    {
        return new FilmHistoryData(
            id: $history->getId(),
            filmId: $history->filmId,
            inventoryType: $history->inventoryType,
            filmType: $history->filmType,
            event: $history->eventType,
            createdAt: $history->getCreatedAt()->format('Y-m-d H:i:s'),
            newSize: round($history->newSize, 2),
            oldSize: round($history->oldSize, 2),
        );
    }
}
