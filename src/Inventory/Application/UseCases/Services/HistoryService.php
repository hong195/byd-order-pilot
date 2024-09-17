<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Services;

use App\Inventory\Application\UseCases\Query\DTO\RecordHistoryUpdatingData;
use App\Inventory\Domain\Aggregate\FilmType;
use App\Inventory\Domain\Aggregate\History;
use App\Inventory\Domain\Repository\FilmRepositoryInterface;
use App\Inventory\Domain\Repository\HistoryRepositoryInterface;

final readonly class HistoryService
{
    /**
     * Class MyClass.
     */
    public function __construct(private HistoryRepositoryInterface $historyRepository, private FilmRepositoryInterface $filmRepository)
    {
    }

    /**
     * Records the adding of history.
     *
     * @param int    $filmId the ID of the film
     * @param string $event  the event to record
     */
    public function recordAdding(int $filmId, string $event): void
    {
        $film = $this->filmRepository->findById($filmId);

        $history = new History(
            filmId: $filmId,
            inventoryType: $this->defineInventoryType($film->getType()),
            filmType: $film->getType(),
            eventType: $event,
            newSize: $film->getLength(),
            oldSize: 0
        );

        $this->historyRepository->add($history);
    }

    /**
     * Records the updating of history.
     *
     * @param RecordHistoryUpdatingData $historyUpdatingData the data for updating the history
     */
    public function recordUpdating(RecordHistoryUpdatingData $historyUpdatingData): void
    {
        $film = $this->filmRepository->findById($historyUpdatingData->filmId);

        $history = new History(
            filmId: $historyUpdatingData->filmId,
            inventoryType: $this->defineInventoryType($film->getType()),
            filmType: $film->getType(),
            eventType: $historyUpdatingData->event,
            newSize: $historyUpdatingData->newSize,
            oldSize: $historyUpdatingData->oldSize
        );

        $this->historyRepository->add($history);
    }

    /**
     * Defines the inventory type based on the given film type.
     *
     * @param string $filmType the film type
     *
     * @return string the inventory type
     */
    private function defineInventoryType(string $filmType): string
    {
        if (in_array($filmType, ['chrome', 'neon', 'white', 'clear', 'eco'])) {
            return FilmType::Film->value;
        }

        return FilmType::LAMINATION->value;
    }
}
