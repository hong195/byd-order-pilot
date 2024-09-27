<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final readonly class SortService
{
    /**
     * Sorts the given collection of jobs based on priority and length.
     *
     * @param Collection<PrintedProduct> $jobs the collection of jobs to be sorted
     *
     * @return Collection the sorted collection of jobs
     */
    public function getSorted(Collection $jobs): Collection
    {
        $jobs = $jobs->toArray();

        usort($jobs, function (PrintedProduct $a, PrintedProduct $b) {
            // Compare sort number
            $sortNumberComparison = $a->getSortOrder() <=> $b->getSortOrder();

            if (0 !== $sortNumberComparison) {
                return $sortNumberComparison;
            }

            // Compare priority (convert boolean to int for comparison)
            $priorityComparison = (int) $b->hasPriority() <=> (int) $a->hasPriority();
            if (0 !== $priorityComparison) {
                return $priorityComparison;
            }

            // Compare film type
            $rollComparison = $b->getFilmType() <=> $a->getFilmType();

            if (0 !== $rollComparison) {
                return $rollComparison;
            }

            return $b->getLength() <=> $a->getLength();
        });

        return new ArrayCollection($jobs);
    }
}
