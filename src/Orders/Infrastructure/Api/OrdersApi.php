<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Api;

use App\Orders\Application\UseCase\PrivateQueryInteractor;
use App\ProductionProcess\Infrastructure\Adapter\Order\OrderApiInterface;

/**
 * OrdersApi class represents an API for handling orders.
 *
 * This class provides functionality related to orders such as creating, updating, and deleting orders.
 */
final readonly class OrdersApi implements OrderApiInterface
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateQueryInteractor $privateQueryInteractor)
    {
    }

    /**
     * Finds a product by its ID.
     *
     * @param int $productId the ID of the product
     *
     * @return \App\Orders\Application\DTO\Product\ProductData the product data
     */
    public function findProductById(int $productId): \App\Orders\Application\DTO\Product\ProductData
    {
        $product = $this->privateQueryInteractor->findAProduct($productId);

        return $product->productData;
    }
}
