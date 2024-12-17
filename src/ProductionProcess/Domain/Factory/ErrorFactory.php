<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Factory;

use App\ProductionProcess\Domain\Aggregate\Error;
use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * Class HistoryFactory.
 *
 * This class is responsible for creating a new History object from a Roll object.
 */
final class ErrorFactory
{
    private Error $error;

    /**
     * Creates a new Error object and assigns it to the $error property of the current object.
     *
     * @param string  $printedProductId      The ID of the printed product associated with the error
     * @param Process $process               The process related to the error
     * @param string  $responsibleEmployeeId The ID of the employee responsible for the error
     * @param string  $noticerId             The ID of the noticer who noticed the error
     *
     * @return self Returns the current object with the updated error property
     */
    public function make(string $printedProductId, Process $process, string $responsibleEmployeeId, string $noticerId): self
    {
        $this->error = new Error(
            noticerId: $noticerId,
            responsibleEmployeeId: $responsibleEmployeeId,
            printedProductId: $printedProductId,
            process: $process
        );

        return $this;
    }

    /**
     * Set a custom message for the Error factory.
     *
     * @param ?string $reason The custom message to be set
     *
     * @return ErrorFactory Returns the ErrorFactory instance for method chaining
     */
    public function withReason(?string $reason): ErrorFactory
    {
        $this->error->setReason($reason);

        return $this;
    }

    /**
     * Returns the error object for this build.
     *
     * @return Error the error object for this build
     */
    public function build(): Error
    {
        return $this->error;
    }
}
