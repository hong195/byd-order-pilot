<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order;

use App\Orders\Domain\Aggregate\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final readonly class SortService implements SortOrdersServiceInterface
{
    /**
     * Sorts the given collection of orders based on priority and length.
     *
     * @param Collection $orders the collection of orders to be sorted
     *
     * @return Collection the sorted collection of orders
     */
    public function getSorted(Collection $orders): Collection
    {
        $orders = $orders->toArray();

        usort($orders, function (Order $a, Order $b) {
            // Compare sort number
            $rollComparison = $b->getFilmType()->value <=> $a->getFilmType()->value;

            if (0 !== $rollComparison) {
                return $rollComparison;
            }

            // Compare sort number
            $sortNumberComparison = $b->getSortOrder() <=> $a->getSortOrder();

            if (0 !== $sortNumberComparison) {
                return $sortNumberComparison;
            }

            // Compare priority (convert boolean to int for comparison)
            $priorityComparison = (int) $b->hasPriority() <=> (int) $a->hasPriority();
            if (0 !== $priorityComparison) {
                return $priorityComparison;
            }

            return $b->getLength() <=> $a->getLength();
        });

        return new ArrayCollection($orders);
    }
}
