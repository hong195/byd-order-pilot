<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;

final class FilmGroup
{
    /**
     * Class constructor for Film object.
     *
     * @param int|null       $filmId   The ID of the film (optional)
     * @param string|null    $filmType The type of the film (optional)
     * @param ProductGroup[] $groups   An array of groups associated with the film
     */
    public function __construct(public ?int $filmId = null, public ?string $filmType = null, private array $groups = [])
    {
    }

    public function make(?int $filmId, ?string $filmType, array $groups = []): self
    {
        return new self($filmId, $filmType, $groups);
    }

    public function getTotalLength(): float|int
    {
        return array_sum(array_map(fn ($group) => $group->getLength(), $this->groups));
    }

    public function addProductGroup(ProductGroup $group): void
    {
        $this->groups[] = $group;
    }

    /**
     * Get the groups associated with the film.
     *
     * @return array The array of groups associated with the film
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * Retrieves all printed products associated with the film.
     *
     * @return PrintedProduct[] an array containing all printed products related to the film
     */
    public function getPrintedProducts(): array
    {
        return array_merge(...array_map(fn ($group) => $group->printedProducts, $this->groups));
    }
}
