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
    private ?int $employeeId = null;
    private ?\DateTimeImmutable $finishedAt = null;

    /**
     * Constructor method.
     *
     * @param int     $rollId  the roll ID
     * @param Process $process the process
     */
    public function __construct(public readonly int $rollId, public readonly Process $process, public readonly \DateTimeImmutable $startedAt)
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
     * Returns the value of the startedAt property.
     *
     * @return \DateTimeImmutable the value of the startedAt property
     */
    public function getStartedAt(): \DateTimeImmutable
    {
        return $this->startedAt;
    }

    /**
     * Get the ended date and time.
     *
     * @return \DateTimeImmutable|null the ended date and time
     */
    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    /**
     * Sets the endedAt property to the current date and time.
     */
    public function finish(): void
    {
        $this->finishedAt = new \DateTimeImmutable();
    }
}
