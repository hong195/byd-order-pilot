<?php

declare(strict_types=1);

namespace App\Orders\Domain\DTO;

final readonly class FilmDataTransformer
{
    public function fromArray(int $id, string $name, float $length, string $type): FilmData
    {
        return new FilmData(
            id: $id,
            name: $name,
            length: $length,
            filmType: $type
        );
    }

    /**
     * Converts an array of films to an array of film data.
     *
     * @param array $films an array of films to convert
     *
     * @return array the converted film data array
     */
    public function fromArrayList(array $films): array
    {
        $filmData = [];
        foreach ($films as $film) {
            $filmData[] = $this->fromArray(
                id: $film['id'],
                name: $film['name'],
                length: $film['length'],
                type: $film['type']
            );
        }

        return $filmData;
    }
}
