<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Repository;

final readonly class ErrorFilter
{
	public function __construct(public ?int $responsibleEmployeeId = null, public ?int $noticerId = null, public ?string $process = null)
	{
	}
}