<?php

namespace App\ProductionProcess\Domain\Repository\PrintedProduct\Error;

use App\ProductionProcess\Application\DTO\Error\EmployeeErrorData;
use App\ProductionProcess\Domain\Aggregate\Error;
use App\Shared\Domain\Repository\DateRangeFilter;

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
     * Find entities by the provided error filter.
     *
     * @param ErrorFilter $filter The filter object to apply when searching for entities
     *
     * @return array<Error> An array of entities that match the provided error filter
     */
    public function findByFilter(ErrorFilter $filter): array;

    /**
     * Find entities by the provided error filter.
     *
     * @param DateRangeFilter $dateRangeFilter The filter object to apply when searching for entities
     *
     * @return EmployeeErrorData[] An array of entities that match the provided error filter
     */
    public function findEmployerErrorsByDateRangeFilter(DateRangeFilter $dateRangeFilter): array;
}
