<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate\Printer;

use App\Shared\Domain\Service\UlidService;

final class Condition
{
    private string $id;

    private \DateTimeImmutable $dateAdded;

    /**
     * Constructor.
     *
     * @param bool $laminationRequired
     */
    public function __construct(public readonly Printer $printer, public string $filmType, public readonly ?string $laminationType = null, public $laminationRequired = false, public readonly ?string $color = null)
    {
        $this->id = UlidService::generate();
        $this->dateAdded = new \DateTimeImmutable();
    }

    /**
     * Get the date when the entity was added.
     */
    public function getDateAdded(): \DateTimeImmutable
    {
        return $this->dateAdded;
    }

    /**
     * Get the ID of the current entity.
     *
     * @return string the ID of the entity
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Checks if the instance is satisfied by a given film type and lamination type.
     *
     * @param string      $filmType       the film type to compare against
     * @param string|null $laminationType the lamination type to compare against, or null if not applicable
     *
     * @return bool true if the instance is satisfied by the given film type and lamination type, false otherwise
     */
    public function isSatisfiedBy(string $filmType, ?string $laminationType = null): bool
    {
        if ($this->filmType !== $filmType) {
            return false;
        }

        if ($this->laminationRequired) {
            return $this->laminationType === $laminationType;
        }

        return true;
    }
}
