<?php

namespace App\ProductionProcess\Domain\Repository;

use App\ProductionProcess\Domain\Aggregate\Job;
use App\ProductionProcess\Domain\ValueObject\Status;

/**
 * This interface represents a Job repository.
 */
interface JobRepositoryInterface
{
    /**
     * Adds a job.
     *
     * @param Job $job the job to be added
     */
    public function add(Job $job): void;

    /**
     * Finds a Job by its ID.
     *
     * @param int $id the ID of the Job to find
     *
     * @return Job|null the found Job object if it exists, null otherwise
     */
    public function findById(int $id): ?Job;

    /**
     * Saves a Job object to the database.
     *
     * @param Job $roll the Job object to be saved
     */
    public function save(Job $roll): void;

    /**
     * Finds records by roll type.
     *
     * @return Job[] an array of records matching the roll type
     */
    public function findByStatus(Status $status): array;

    //    /**
    //     * Finds records based on the given JobFilter.
    //     *
    //     * @param JobFilter $rollFilter the filter to query records
    //     *
    //     * @return Job[] an array of matching records
    //     */
    //    public function findByFilter(JobFilter $rollFilter): array;

    /**
     * Finds a Job by its Film ID.
     *
     * @param int $filmId the Film ID of the Job to find
     *
     * @return Job|null the found Job object if it exists, null otherwise
     */
    public function findByFilmId(int $filmId): ?Job;
}
