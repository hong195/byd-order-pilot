<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Adapter\Order;

final readonly class OrdersAdapter
{
    public function __construct(private OrderApiInterface $adapter)
    {
    }

    /**
     * Finds a product by its ID.
     *
     * @param int $productId the ID of the product to find
     *
     * @return mixed the found product or null if not found
     */
    public function findProductById(int $productId): mixed
    {
        return $this->adapter->findProductById($productId);
    }


}
