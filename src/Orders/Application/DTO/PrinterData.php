<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

use App\Orders\Domain\Aggregate\Printer;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;

/**
 * Class RollData.
 *
 * Represents a printer data.
 */
final readonly class PrinterData
{
    /**
     * Constructor method for the class.
     *
     * @param int      $id          the ID of the object
     * @param string[] $filmTypes   an array of film types
     * @param string[] $laminations an array of laminations
     */
    public function __construct(public int $id, public array $filmTypes = [], public array $laminations = [])
    {
    }

    /**
     * Converts an array of entities into an array of DTOs.
     *
     * @param Printer[] $entities An array of entities to convert
     *
     * @return self[] An array of DTOs
     */
    public function fromEntityList(array $entities): array
    {
        $dtos = [];
        foreach ($entities as $entity) {
            $dtos[] = new self(
                id: $entity->getId(),
                filmTypes: array_map(
                    fn (FilmType $filmType) => $filmType->value,
                    $entity->getFilmTypes()
                ),
                laminations: array_map(
                    fn (LaminationType $lamination) => $lamination->value,
                    $entity->getLaminationTypes()
                )
            );
        }

        return $dtos;
    }
}
