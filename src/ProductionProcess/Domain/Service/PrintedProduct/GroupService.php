<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
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
     * @param Collection<PrintedProduct> $printedProducts The collection of jobs
     *
     * @return array<int, Collection<PrintedProduct>> The filtered jobs grouped by lamination type
     */
    public function handle(Collection $printedProducts): array
    {
        $result = [];

        $laminations = $this->getLaminationGroupFromJobs($printedProducts);

        foreach ($laminations as $lamination) {
            $result[] = $this->sortJobsService->getSorted($printedProducts)->filter(function ($job) use ($lamination) {
                return $job->getLaminationType() === $lamination;
            });
        }

        return $result;
    }

    /**
     * Get lamination group from jobs.
     *
     * @param Collection<PrintedProduct> $printedProducts the collection of jobs
     *
     * @return string[] the array of lamination types
     */
    private function getLaminationGroupFromJobs(Collection $printedProducts): array
    {
        $laminations = [];

		/** @var PrintedProduct $printedProduct */
		foreach ($this->sortJobsService->getSorted($printedProducts) as $printedProduct) {
            $laminations[] = $printedProduct->getLaminationType();
        }

        $laminations = array_unique($laminations);

        return $laminations;
    }
}
