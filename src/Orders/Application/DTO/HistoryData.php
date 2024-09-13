<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

use App\Orders\Domain\Aggregate\Roll\History\History;

/**
 * Class HistoryData.
 *
 * Represents history data for an object.
 */
final class HistoryData
{
    private ?EmployeeData $employee = null;

    /**
     * Class constructor.
     *
     * @param int|null                $id         The ID of the object. Default is null.
     * @param int|null                $rollId     The roll ID of the object. Default is null.
     * @param \DateTimeInterface|null $happenedAt The happened at date and time of the object. Default is null.
     */
    public function __construct(public readonly ?int $id = null, public readonly ?int $rollId = null, public ?string $process = null, public readonly ?\DateTimeInterface $happenedAt = null)
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
