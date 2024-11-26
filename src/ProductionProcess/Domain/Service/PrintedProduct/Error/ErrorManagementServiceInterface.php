<?php

namespace App\ProductionProcess\Domain\Service\PrintedProduct\Error;

use App\ProductionProcess\Domain\ValueObject\Process;

interface ErrorManagementServiceInterface
{
	public function recordError(int $printedProductId, Process $process, int $noticerId, ?string $reason = null): void;
}