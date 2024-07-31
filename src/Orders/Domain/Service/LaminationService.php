<?php

namespace App\Orders\Domain\Service;

use App\Orders\Domain\Aggregate\Lamination\Lamination;
use App\Orders\Domain\Aggregate\Lamination\LaminationType;
use App\Orders\Domain\Aggregate\Quality;
use App\Orders\Domain\Factory\LaminationFactory;
use App\Orders\Infrastructure\Repository\LaminationRepository;
use App\Shared\Domain\Service\AssertService;

final readonly class LaminationService
{
    public function __construct(private LaminationRepository $laminationRepository)
    {
    }

    public function addLamination(string $name, string $quality, string $laminationType): int
    {
        $lamination = (new LaminationFactory())
            ->create(
                name: $name,
                quality: $quality,
                laminationType: $laminationType
            );

        $this->laminationRepository->add($lamination);

        return $lamination->getId();
    }

    public function getLamination(int $id): Lamination
    {
        $lamination = $this->laminationRepository->findById($id);
        AssertService::notNull($lamination, 'Lamination not found');

        return $lamination;
    }

    public function updateLamination(
        int $id, string $name, string $quality,
        string $laminationType, int $length,
        int $priority = 0,
        ?string $qualityNotes = null
    ): void {
        $lamination = $this->getLamination($id);
        $lamination->changeName($name);

        if ($length !== $lamination->getLength()) {
            $lamination->updateLength($length);
        }

        if ($qualityNotes !== $lamination->getQualityNotes()) {
            $lamination->changeQualityNotes($qualityNotes);
        }

        if ($quality !== $lamination->getQuality()) {
            $lamination->changeQuality(Quality::from($quality));
        }

        if ($priority !== $lamination->getPriority()) {
            $lamination->changePriority($priority);
        }

        if ($laminationType !== $lamination->getLaminationType()) {
            $lamination->changeLaminationType(LaminationType::from($laminationType));
        }

        $this->laminationRepository->save($lamination);
    }

    public function deleteLamination(int $id): void
    {
        $lamination = $this->getLamination($id);
        $this->laminationRepository->remove($lamination);
    }
}
