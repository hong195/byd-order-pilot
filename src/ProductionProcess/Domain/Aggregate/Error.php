<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate;

use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Service\UlidService;

class Error
{
    private string $id;
    private ?string $reason = null;
    private \DateTimeInterface $createdAt;

    /**
     * Class constructor.
     */
    public function __construct(public readonly string $noticerId, public readonly string $responsibleEmployeeId, public readonly string $printedProductId, public readonly Process $process)
    {
        $this->id = UlidService::generate();
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Get the ID property.
     *
     * @return string The ID of the entity
     */
    public function getId(): string
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
     * @return string|null The message value
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * Set the message property.
     *
     * @param string $reason The message to set
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }
}
