<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Job;

use App\ProductionProcess\Domain\Aggregate\Job;
use Doctrine\Common\Collections\Collection;

/**
 * Handles a collection of jobs and filters them based on their lamination type.
 *
 * @param iterable $jobs The collection of jobs
 *
 * @return array The filtered jobs grouped by lamination type
 */
final readonly class GroupService
{
	/**
	 * Class Constructor.
	 *
	 * @param SortService $sortJobsService The SortService instance used for sorting jobs.
	 */
    public function __construct(private SortService $sortJobsService)
    {
    }

    /**
     * Handles a collection of jobs and filters them based on their lamination type.
     *
     * @param Collection<Job> $jobs The collection of jobs
     *
     * @return array<int, Collection<Job>> The filtered jobs grouped by lamination type
     */
    public function handle(Collection $jobs): array
    {
        $result = [];

        $laminations = $this->getLaminationGroupFromJobs($jobs);

        foreach ($laminations as $lamination) {
            $result[] = $this->sortJobsService->getSorted($jobs)->filter(function ($job) use ($lamination) {
                return $job->getLaminationType()?->value === $lamination;
            });
        }

        return $result;
    }

    /**
     * Get lamination group from jobs.
     *
     * @param Collection<Job> $jobs the collection of jobs
     *
     * @return string[] the array of lamination types
     */
    private function getLaminationGroupFromJobs(Collection $jobs): array
    {
        $laminations = [];

        foreach ($this->sortJobsService->getSorted($jobs) as $job) {
            $laminations[] = $job->getLaminationType()?->value;
        }

        $laminations = array_unique($laminations);

        return $laminations;
    }
}
