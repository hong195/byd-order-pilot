<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate\Printer;

/**
 *
 */
final class Condition
{
    /**
     * @phpstan-ignore-next-line
     */
    private int $id;

    private \DateTimeImmutable $dateAdded;

    /**
     * Class constructor for creating a new instance.
     *
     * @param Printer     $printer            the printer object to be injected into the instance
     * @param string      $filmType           the type of film used by the instance
     * @param string|null $laminationType     the type of lamination for the instance, or null if not applicable
     * @param bool|null   $laminationRequired whether lamination is required for the instance, false by default
     */
    public function __construct(public readonly Printer $printer, public string $filmType, public readonly ?string $laminationType = null, public readonly ?bool $laminationRequired = false, public readonly ?string $color = null)
    {
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
     * @return int the ID of the entity
     */
    public function getId(): int
    {
        return $this->id;
    }
}
