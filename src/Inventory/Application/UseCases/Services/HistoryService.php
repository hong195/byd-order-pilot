<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Services;

use App\Inventory\Application\UseCases\Query\DTO\RecordHistoryUpdatingData;
use App\Inventory\Domain\Aggregate\History;
use App\Inventory\Domain\Repository\FilmRepositoryInterface;
use App\Inventory\Domain\Repository\HistoryRepositoryInterface;

final readonly class HistoryService
{
    public function __construct(private HistoryRepositoryInterface $historyRepository, private FilmRepositoryInterface $filmRepository)
    {
    }

    public function recordAdding(int $filmId, string $event): void
    {
        $film = $this->filmRepository->findById($filmId);

        $history = new History(
            filmId: $filmId,
            filmType: $film->getType(),
            eventType: $event,
            newSize: $film->getLength(),
            oldSize: 0
        );

        $this->historyRepository->add($history);
    }

    public function recordUpdating(RecordHistoryUpdatingData $historyUpdatingData): void
    {
        $film = $this->filmRepository->findById($historyUpdatingData->filmId);

        $history = new History(
            filmId: $historyUpdatingData->filmId,
            filmType: $film->getType(),
            eventType: $historyUpdatingData->event,
            newSize: $historyUpdatingData->newSize,
            oldSize: $historyUpdatingData->oldSize
        );

        $this->historyRepository->add($history);
    }
}
