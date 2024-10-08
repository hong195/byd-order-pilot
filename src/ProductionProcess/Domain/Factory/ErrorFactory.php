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
     * Makes a new Error instance with provided details.
     *
     * @param int     $printedProductId      The ID of the printed product related to the Error
     * @param Process $process               The process associated with the Error
     * @param int     $responsibleEmployeeId The ID of the responsible employee for the Error
     * @param int     $noticerId             The ID of the user who noticed the Error
     *
     * @return self Returns the instance of the class for method chaining
     */
    public function make(int $printedProductId, Process $process, int $responsibleEmployeeId, int $noticerId): self
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
        $this->error->setMessage($reason);

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
