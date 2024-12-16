<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate\Roll\History;

use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Service\UlidService;

/**
 * Class History.
 *
 * Represents a historical record of roll.
 */
final class History
{
    /**@phpstan-ignore-next-line */
    private string $id;
    private ?string $employeeId = null;
    private ?int $parentRollId = null;

    /**
     * Constructor method.
     *
     * @param int     $rollId  the roll ID
     * @param Process $process the process
     */
    public function __construct(public readonly string $rollId, public readonly Process $process, public readonly Type $type, public readonly \DateTimeImmutable $happenedAt)
    {
        $this->id = UlidService::generate();
    }

    /**
     * Retrieves the id of the object.
     *
     * @return string the id of the object
     */
    public function getId(): string
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
    public function setEmployeeId(?string $employeeId): void
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
