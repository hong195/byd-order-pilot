<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate\Roll;

/**
 * Class History.
 *
 * Represents a historical record of roll.
 */
final class History
{
    /**@phpstan-ignore-next-line */
    private ?int $id;
    private \DateTimeImmutable $from;
    private ?\DateTimeInterface $startedAt = null;
    private ?\DateTimeInterface $endedAt = null;
    private ?int $employeeId = null;

    /**
     * Class constructor.
     *
     * @param int    $rollId       the roll id
     * @param int    $parentRollId the parent roll id
     * @param string $status       the status
     */
    public function __construct(public readonly int $rollId, public readonly int $parentRollId, public readonly string $status)
    {
        $this->from = new \DateTimeImmutable();
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
     * Get the "from" datetime.
     *
     * @return \DateTimeImmutable the "from" datetime
     */
    public function getFrom(): \DateTimeImmutable
    {
        return $this->from;
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
     * Get the endedAt datetime.
     *
     * @return \DateTimeInterface|null the endedAt datetime or null if not set
     */
    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->endedAt;
    }

    /**
     * Set the end datetime.
     *
     * @param \DateTimeInterface $endedAt the end datetime
     */
    public function setEndedAt(\DateTimeInterface $endedAt): void
    {
        $this->endedAt = $endedAt;
    }

    /**
     * Get the date and time when the task started.
     *
     * @return \DateTimeInterface the date and time when the task started
     */
    public function getStartedAt(): \DateTimeInterface
    {
        return $this->startedAt;
    }

    /**
     * Set "to" date.
     *
     * @param \DateTimeInterface $startedAt The "to" date to be set
     */
    public function setStartedAt(\DateTimeInterface $startedAt): void
    {
        $this->startedAt = $startedAt;
    }
}
