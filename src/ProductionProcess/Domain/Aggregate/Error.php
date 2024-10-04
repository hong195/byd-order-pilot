<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate;

use App\ProductionProcess\Domain\ValueObject\Process;

class Error
{
    /**
     * @phpstan-ignore-next-line
     */
    private ?int $id;
    private ?string $message = null;
    private \DateTimeInterface $createdAt;

    /**
     * Constructor for creating a new instance of the specified class.
     *
     * @param int     $noticerId             The ID of the noticer
     * @param int     $responsibleEmployeeId The ID of the responsible employee
     * @param int     $rollId                The ID of the roll
     * @param Process $process               The process associated with the instance
     */
    public function __construct(public readonly int $noticerId, public readonly int $responsibleEmployeeId, public readonly int $rollId, public readonly Process $process)
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Get the ID property.
     *
     * @return int The ID of the entity
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the createdAt property.
     *
     * @return \DateTimeInterface The createdAt value
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Get the message property.
     *
     * @return string The message value
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set the message property.
     *
     * @param string $message The message to set
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
