<?php

declare(strict_types=1);

namespace App\Rolls\Application\DTO;

use App\Rolls\Domain\Aggregate\Roll\Roll;

/**
 * RollDataTransformer class is responsible for converting an array of Roll entities into an array of RollData instances.
 */
final readonly class RollDataTransformer
{
    /**
     * Converts an array of Roll entities into an array of RollData instances.
     *
     * @param Roll[] $rollsEntityList an array of Roll entities to convert
     *
     * @return RollData[] an array of RollData instances
     */
    public function fromRollsEntityList(array $rollsEntityList): array
    {
        $rollsData = [];
        foreach ($rollsEntityList as $rollEntity) {
            $rollsData[] = $this->fromEntity($rollEntity);
        }

        return $rollsData;
    }

    /**
     * Creates a new instance of the class from a Roll entity.
     *
     * @param Roll $roll the Roll entity from which to create the instance
     *
     * @return RollData the newly created instance
     */
    public function fromEntity(Roll $roll): RollData
    {
        return new RollData(
            id: $roll->getId(),
            name: $roll->getName(),
            length: $roll->getLength(),
            quality: $roll->getQuality(),
            rollType: $roll->getRollType(),
            dateAdded: $roll->getDateAdded(),
            qualityNotes: $roll->getQualityNotes()
        );
    }
}
