<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

/**
 * Class RollData.
 *
 * Represents a roll of data.
 */
final class RollData
{
    private ?EmployeeData $employee = null;
    private ?PrinterData $printer = null;

    /**
     * Constructor for the class.
     *
     * @param int                     $id            the ID of the object
     * @param string                  $name          the name of the object
     * @param int                     $length        the length of the object
     * @param int                     $count         the count of the object
     * @param string[]                $films         an array of films
     * @param string|null             $process       the process of the object (optional)
     * @param int                     $priorityCount the priority count of the object (default: 0)
     * @param array                   $laminations   an array of laminations
     * @param int|null                $filmId        the film ID of the object (optional)
     * @param \DateTimeInterface|null $dateAdded     the date added of the object (optional)
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $length,
        public readonly int $count,
        public readonly array $films,
        public readonly ?string $process = null,
        public readonly int $priorityCount = 0,
        public readonly array $laminations = [],
        public readonly ?int $filmId = null,
        public readonly ?\DateTimeInterface $dateAdded = null,
    ) {
    }

    public function withEmployee(?EmployeeData $employee): void
    {
        $this->employee = $employee;
    }

    public function withPrinter(?Printerdata $printer): void
    {
        $this->printer = $printer;
    }

    /**
     * Getter method to retrieve the employee object.
     *
     * @return EmployeeData|null the employee object if set, null otherwise
     */
    public function getEmployee(): ?EmployeeData
    {
        return $this->employee;
    }

    /**
     * Getter method to retrieve the printer object.
     *
     * @return PrinterData|null the printer object if set, null otherwise
     */
    public function getPrinter(): ?PrinterData
    {
        return $this->printer;
    }
}
