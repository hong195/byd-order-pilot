<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate\Roll;

use App\Orders\Domain\ValueObject\Process;

/**
 * Class History.
 *
 * Represents a historical record of roll.
 */
final class History
{
    /**@phpstan-ignore-next-line */
    private ?int $id;
    public ?int $parentRollId = null;
    private ?int $employeeId = null;
    public ?\DateTimeImmutable $endedAt = null;

    /**
     * Constructor method.
     *
     * @param int                     $rollId    the roll ID
     * @param Process                 $process   the process
     * @param \DateTimeImmutable|null $startedAt the started at date
     */
    public function __construct(public readonly int $rollId, public readonly Process $process, public readonly ?\DateTimeImmutable $startedAt)
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

    /**
     * Get the ended date and time.
     *
     * @return \DateTimeImmutable|null the ended date and time
     */
    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    /**
     * Sets the endedAt property to the current date and time.
     */
    public function end(): void
    {
        $this->endedAt = new \DateTimeImmutable();
    }
}
