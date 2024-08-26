<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Repository;

/**
 * Class FilmFilter.
 *
 * Represents a filter for films.
 */
final readonly class FilmFilter
{
    /**
     * Class constructor.
     *
     * @param string|null $inventoryType the film type
     * @param string|null $name          the name
     * @param string|null $type          the type
     */
    public function __construct(public ?string $inventoryType = null, public ?string $name = null, public ?string $type = null)
    {
    }
}
