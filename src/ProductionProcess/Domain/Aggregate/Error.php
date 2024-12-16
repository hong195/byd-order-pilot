<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate;

use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Service\UlidService;

class Error
{
    /**
     * @phpstan-ignore-next-line
     */
    private string $id;
    private ?string $reason = null;
    private \DateTimeInterface $createdAt;

    /**
     * Constructor for creating a new instance of the specified class.
     *
     * @param int     $noticerId             The ID of the noticer
     * @param int     $responsibleEmployeeId The ID of the responsible employee
     * @param int     $printedProductId      The ID of the printed product
     * @param Process $process               The process associated with the instance
     */
    public function __construct(public readonly int $noticerId, public readonly int $responsibleEmployeeId, public readonly string $printedProductId, public readonly Process $process)
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
