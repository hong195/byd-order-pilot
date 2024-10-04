<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Repository\InMemory;

use App\ProductionProcess\Domain\Aggregate\Error;
use App\ProductionProcess\Domain\Repository\ErrorRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * Class ErrorRepository.
 */
class ErrorRepository implements ErrorRepositoryInterface
{
    private array $errors = [];

    /**
     * Add an error to the list of errors.
     *
     * @param Error $error The error to add to the list
     */
    public function add(Error $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * Find errors by responsible employee ID.
     *
     * @param int $responsibleEmployeeId The ID of the responsible employee to search for
     *
     * @return array<Error> An array of errors associated with the specified responsible employee
     */
    public function findByResponsibleEmployeeId(int $responsibleEmployeeId): array
    {
        return array_filter($this->errors, function (Error $error) use ($responsibleEmployeeId) {
            return $error->responsibleEmployeeId === $responsibleEmployeeId;
        });
    }

    /**
     * Finds errors by a specific process.
     *
     * @param Process $process the process to filter errors by
     *
     * @return array<Error> an array of errors associated with the provided process
     */
    public function findByProcess(Process $process): array
    {
        return array_filter($this->errors, function (Error $error) use ($process) {
            return $error->process === $process;
        });
    }
}
