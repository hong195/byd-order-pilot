<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO;

use App\ProductionProcess\Domain\Aggregate\Roll\History\History;

/**
 * Class HistoryData.
 *
 * Represents history data for an object.
 */
final class HistoryData
{
    private ?EmployeeData $employee = null;

    /**
     * Constructor for the class.
     *
     * @param string|null                $id         The unique identifier, or null if not assigned
     * @param string|null                $rollId     The associated roll ID, or null if not assigned
     * @param string|null             $type       The type, or null if not specified
     * @param string|null             $process    The process name, or null if not specified
     * @param \DateTimeInterface|null $happenedAt The date and time when the event happened, or null if not available
     */
    public function __construct(public readonly ?string $id = null, public readonly ?string $rollId = null, public ?string $type = null, public ?string $process = null, public readonly ?\DateTimeInterface $happenedAt = null)
    {
    }

    /**
     * Create a new HistoryData object from a History object.
     *
     * @param History $history the History object to create from
     *
     * @return HistoryData the new HistoryData object
     */
    public function fromHistory(History $history): HistoryData
    {
        return new self(
            id: $history->getId(),
            rollId: $history->rollId,
            type: $history->type->value,
            process: $history->process->value,
            happenedAt: $history->happenedAt,
        );
    }

    /**
     * Set the employee data.
     *
     * @param ?EmployeeData $employeeData the employee data to set
     */
    public function withEmployee(?EmployeeData $employeeData): void
    {
        $this->employee = $employeeData;
    }

    /**
     * Get the employee data.
     *
     * @return EmployeeData|null the employee data, or null if not available
     */
    public function getEmployee(): ?EmployeeData
    {
        return $this->employee;
    }
}
