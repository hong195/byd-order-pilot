<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto\Groups;

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
    public function __construct(public ?string $filmId = null, public ?string $filmType = null, private array $groups = [])
    {
    }

    /**
     * Creates a new instance of the current class with the provided film details and groups.
     *
     * @param int|null       $filmId   The ID of the film (optional)
     * @param string|null    $filmType The type of the film (optional)
     * @param ProductGroup[] $groups   An array of groups associated with the film
     *
     * @return self A new instance of the current class with the provided film details and groups
     */
    public function make(?string $filmId, ?string $filmType, array $groups = []): self
    {
        return new self($filmId, $filmType, $groups);
    }

    /**
     * Get the total length of all groups associated with the film.
     *
     * @return float|int The total length of all groups associated with the film
     */
    public function getTotalLength(): float|int
    {
        return array_sum(array_map(fn ($group) => $group->getLength(), $this->groups));
    }

    /**
     * Add a ProductGroup to the array of groups associated with the Film object.
     *
     * @param ProductGroup $group The ProductGroup object to add to the groups array
     */
    public function addProductGroup(ProductGroup $group): void
    {
        $this->groups[] = $group;
    }

    /**
     * Get the groups associated with the film.
     *
     * @return ProductGroup[] An array of groups associated with the film
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * Get an array of all printed products associated with the film.
     *
     * @return PrintedProduct[] An array of printed products
     */
    public function getPrintedProducts(): array
    {
        return array_merge(...array_map(fn ($group) => $group->printedProducts, $this->groups));
    }
}
