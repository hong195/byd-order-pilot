<?php

namespace App\ProductionProcess\Domain\Repository;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\ValueObject\Status;
use App\Shared\Domain\Repository\PaginationResult;

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
     * @param PrintedProduct $printedProduct the Job object to be saved
     */
    public function save(PrintedProduct $printedProduct): void;

    /**
     * Finds records by roll type.
     *
     * @return PrintedProduct[] an array of records matching the roll type
     */
    public function findByStatus(Status $status): array;

    /**
     * Finds PrintedProducts based on a specified filter.
     *
     * @param PrintedProductFilter $filter the filter to apply on the PrintedProducts
     *
     * @return PrintedProduct[] the result of the pagination
     */
    public function findByFilter(PrintedProductFilter $filter): array;
}
