<?php

namespace App\ProductionProcess\Infrastructure\Repository\InMemory;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Repository\PrintedProductFilter;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Status;

/**
 * Class PrintedProductRepository.
 */
class PrintedProductRepository implements PrintedProductRepositoryInterface
{
    /**
     * @var array<PrintedProduct>
     */
    private array $printedProducts = [];

    /**
     * Saves a PrintedProduct entity.
     *
     * @param PrintedProduct $printedProduct The PrintedProduct entity to save
     */
    public function save(PrintedProduct $printedProduct): void
    {
        $this->printedProducts[$printedProduct->getId()] = $printedProduct;
    }

    /**
     * Finds a PrintedProduct by its ID.
     *
     * @param int $id The ID of the PrintedProduct to find
     *
     * @return PrintedProduct|null The found PrintedProduct if exists, otherwise null
     */
    public function findById(int $id): ?PrintedProduct
    {
        return $this->printedProducts[$id] ?? null;
    }

    /**
     * Adds a PrintedProduct to the collection.
     *
     * @param PrintedProduct $job The PrintedProduct to add
     */
    public function add(PrintedProduct $job): void
    {
        $this->printedProducts[$job->getId()] = $job;
    }

    /**
     * Find and return an array of jobs that have the given status.
     *
     * @param Status $status The status to search for
     *
     * @return array An array of jobs with the specified status
     */
    public function findByStatus(Status $status): array
    {
        $jobs = [];
        foreach ($this->printedProducts as $job) {
            if ($job->getStatus() === $status) {
                $jobs[] = $job;
            }
        }

        return $jobs;
    }

    /**
     * Finds printed products based on the provided filter.
     *
     * @param PrintedProductFilter $filter The filter to apply
     *
     * @return array The array of matching printed products
     */
    public function findByFilter(PrintedProductFilter $filter): array
    {
        $printedProducts = [];
        foreach ($this->printedProducts as $printedProduct) {
            if ($filter->unassigned && null === $printedProduct->getRoll()?->getId()) {
                $printedProducts[] = $printedProduct;
            }

            if ($printedProduct->getRoll()?->getId() === $filter->rollId) {
                $printedProducts[] = $printedProduct;
            }
        }

        return $printedProducts;
    }
}
