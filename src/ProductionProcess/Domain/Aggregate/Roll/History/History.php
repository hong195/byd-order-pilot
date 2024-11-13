<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate\Roll\History;

use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * Class History.
 *
 * Represents a historical record of roll.
 */
final class History
{
    /**@phpstan-ignore-next-line */
    private ?int $id;
    private ?int $employeeId = null;
    private ?int $parentRollId = null;

    /**
     * Constructor method.
     *
     * @param int     $rollId  the roll ID
     * @param Process $process the process
     */
    public function __construct(public readonly int $rollId, public readonly Process $process, public readonly Type $type, public readonly \DateTimeImmutable $happenedAt)
    {
    }

    /**
     * Retrieves the id of the object.
     *
     * @return int the id of the object
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the employee ID.
     *
     * @return int|null the employee ID
     */
    public function getEmployeeId(): ?int
    {
        return $this->employeeId;
    }

    /**
     * Set the employee ID.
     *
     * @param int|null $employeeId the employee ID
     */
    public function setEmployeeId(?int $employeeId): void
    {
        $this->employeeId = $employeeId;
    }

    /**
     * Returns the value of the happenedAt property.
     *
     * @return \DateTimeImmutable the value of the happenedAt property
     */
    public function getHappenedAt(): \DateTimeImmutable
    {
        return $this->happenedAt;
    }

    /**
     * Get the parent roll ID.
     *
     * @return int|null the parent roll ID
     */
    public function getParentRollId(): ?int
    {
        return $this->parentRollId;
    }

    /**
     * Set the parent roll ID.
     *
     * @param int|null $parentRollId the parent roll ID
     */
    public function setParentRollId(?int $parentRollId): void
    {
        $this->parentRollId = $parentRollId;
    }
}
