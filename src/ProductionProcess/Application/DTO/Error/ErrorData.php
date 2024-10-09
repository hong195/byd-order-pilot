<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\Error;

final readonly class ErrorData
{
    /**
     * Class constructor.
     */
    public function __construct(public int $id, public string $process, public string $reason, public int $responsibleEmployee, public int $noticer, public \DateTimeInterface $createdAt)
    {
    }
}
