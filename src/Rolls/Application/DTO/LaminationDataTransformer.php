<?php

namespace App\Rolls\Application\DTO;

use App\Rolls\Domain\Aggregate\Lamination\Lamination;

/**
 * Class LaminationDataTransformer.
 *
 * Converts Lamination entities to LaminationData objects.
 */
final readonly class LaminationDataTransformer
{
    /**
     * Converts an array of lamination entities into an array of lamination data.
     *
     * @param Lamination[] $laminationEntities an array of lamination entities
     *
     * @return LaminationData[] $laminationData an array of lamination data
     */
    public function fromRollsEntityList(array $laminationEntities): array
    {
        $laminationData = [];
        foreach ($laminationEntities as $laminationEntity) {
            $laminationData[] = $this->fromEntity($laminationEntity);
        }

        return $laminationData;
    }

    /**
     * Converts a Lamination entity object to a LaminationData object.
     *
     * @param Lamination $lamination the Lamination entity object to convert
     *
     * @return LaminationData the converted LaminationData object
     */
    public function fromEntity(Lamination $lamination): LaminationData
    {
        return new LaminationData(
            id: $lamination->getId(),
            name: $lamination->getName(),
            length: $lamination->getLength(),
            quality: $lamination->getQuality(),
            laminationType: $lamination->getLaminationType(),
            dateAdded: $lamination->getDateAdded(),
            qualityNotes: $lamination->getQualityNotes()
        );
    }
}
