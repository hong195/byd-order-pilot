<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order;

use App\Orders\Domain\Aggregate\Order;
use Doctrine\Common\Collections\Collection;

/**
 * Handles a collection of orders and filters them based on their lamination type.
 *
 * @param iterable $orders The collection of orders
 *
 * @return array The filtered orders grouped by lamination type
 */
final readonly class GroupService
{
    /**
     * Class constructor.
     *
     * @param SortOrdersServiceInterface $sortOrdersService the sort orders service
     */
    public function __construct(private SortOrdersServiceInterface $sortOrdersService)
    {
    }

    /**
     * Handles a collection of orders and filters them based on their lamination type.
     *
     * @param Collection<Order> $orders The collection of orders
     *
     * @return array<int, Collection<Order>> The filtered orders grouped by lamination type
     */
    public function handle(Collection $orders): array
    {
        $result = [];

        $laminations = $this->getLaminationGroupFromOrders($orders);

        foreach ($laminations as $lamination) {
            $result[] = $this->sortOrdersService->getSorted($orders)->filter(function ($order) use ($lamination) {
                return $order->getLaminationType()?->value === $lamination;
            });
        }

        return $result;
    }

    /**
     * Get lamination group from orders.
     *
     * @param Collection $orders the collection of orders
     *
     * @return string[] the array of lamination types
     */
    private function getLaminationGroupFromOrders(Collection $orders): array
    {
        $laminations = [];

        foreach ($this->sortOrdersService->getSorted($orders) as $order) {
            $laminations[] = $order->getLaminationType()?->value;
        }

        $laminations = array_unique($laminations);

        return $laminations;
    }
}
