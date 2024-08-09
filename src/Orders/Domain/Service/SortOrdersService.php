<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service;

use App\Orders\Domain\Aggregate\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final readonly class SortOrdersService implements SortOrdersServiceInterface
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
            if ($a->getLaminationType()?->value > $b->getLaminationType()?->value) {
                return 1;
            } elseif ($a->getLaminationType()?->value < $b->getLaminationType()?->value) {
                return -1;
            }

            if ((int) $a->hasPriority() > (int) $b->hasPriority()) {
                return 1;
            } elseif ((int) $a->hasPriority() < (int) $b->hasPriority()) {
                return -1;
            }

            if ($a->getLength() > $b->getLength()) {
                return -1;
            } elseif ($a->getLength() < $b->getLength()) {
                return 1;
            }

            return 0;
        });

        return new ArrayCollection($orders);
    }
}
