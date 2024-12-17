<?php

namespace App\ProductionProcess\Domain\Service\PrintedProduct\Error;

use App\ProductionProcess\Domain\ValueObject\Process;

interface ErrorManagementServiceInterface
{
    public function recordError(string $printedProductId, Process $process, string $noticerId, ?string $reason = null): void;
}
