<?php

namespace App\ProductionProcess\Domain\Repository;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\ValueObject\Status;

/**
 * This interface represents a Job repository.
 */
interface PrintedProductRepositoryInterface
{
    /**
     * Adds a job.
     *
     * @param PrintedProduct $job the job to be added
     */
    public function add(PrintedProduct $job): void;

    /**
     * Finds a Job by its ID.
     *
     * @param int $id the ID of the Job to find
     *
     * @return PrintedProduct|null the found Job object if it exists, null otherwise
     */
    public function findById(int $id): ?PrintedProduct;

    /**
     * Saves a Job object to the database.
     *
     * @param PrintedProduct $roll the Job object to be saved
     */
    public function save(PrintedProduct $roll): void;

    /**
     * Finds records by roll type.
     *
     * @return PrintedProduct[] an array of records matching the roll type
     */
    public function findByStatus(Status $status): array;
}
