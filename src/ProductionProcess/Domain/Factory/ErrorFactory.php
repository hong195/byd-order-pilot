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
     * Initialize a new Error instance and set its properties.
     *
     * @param int     $noticerId             The ID of the noticer
     * @param int     $responsibleEmployeeId The ID of the responsible employee
     * @param int     $rollId                The ID of the roll
     * @param Process $process               The process associated with the error
     *
     * @return self Returns the current instance of the class for method chaining
     */
    public function make(int $noticerId, int $responsibleEmployeeId, int $rollId, Process $process): self
    {
        $this->error = new Error(
            noticerId: $noticerId,
            responsibleEmployeeId: $responsibleEmployeeId,
            rollId: $rollId,
            process: $process
        );

        return $this;
    }

    /**
     * Set a custom message for the Error factory.
     *
     * @param string $message The custom message to be set
     *
     * @return ErrorFactory Returns the ErrorFactory instance for method chaining
     */
    public function withMessage(string $message): ErrorFactory
    {
        $this->error->setMessage($message);
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
