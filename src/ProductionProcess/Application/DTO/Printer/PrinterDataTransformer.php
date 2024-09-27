<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\Printer;

use App\ProductionProcess\Domain\Aggregate\Printer;
use App\ProductionProcess\Domain\ValueObject\FilmType;
use App\ProductionProcess\Domain\ValueObject\LaminationType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class RollData.
 *
 * Represents a printer data.
 */
final readonly class PrinterDataTransformer
{
    /**
     * Converts an array of entities into an array of DTOs.
     *
     * @param iterable<Printer> $entities An array of entities to convert
     *
     * @return Collection<PrinterData> An array of DTOs
     */
    public function fromEntityList(iterable $entities): Collection
    {
        $dtos = new ArrayCollection();
        foreach ($entities as $entity) {
            $dtos[] = new PrinterData(
                id: $entity->getId(),
                name: $entity->getName(),
                filmTypes: $entity->getFilmTypes(),
                laminations: $entity->getLaminationTypes()
            );
        }

        return $dtos;
    }
}
