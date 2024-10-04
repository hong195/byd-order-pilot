<?php

namespace App\ProductionProcess\Domain\Repository;

use App\ProductionProcess\Domain\Aggregate\Error;
use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * Interface ErrorRepositoryInterface.
 */
interface ErrorRepositoryInterface
{
    /**
     * Saves a history record.
     */
    public function add(Error $error): void;

    /**
     * Find entities by the responsible employee ID.
     *
     * @param int $responsibleEmployeeId The ID of the responsible employee to search for
     *
     * @return array<Error> An array of entities that match the responsible employee ID
     */
    public function findByResponsibleEmployeeId(int $responsibleEmployeeId): array;

    /**
     * Finds process by given object of type Process.
     *
     * @param Process $process the process object to search for
     *
     * @return array<Error> an array of process objects that match the given Process
     */
    public function findByProcess(Process $process): array;
}
