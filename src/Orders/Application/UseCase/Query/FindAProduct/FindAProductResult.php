<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindAProduct;

use App\Orders\Application\DTO\Order\OrderData;
use App\Orders\Application\DTO\Product\ProductData;

/**
 * Represents the result of finding an order.
 */
final readonly class FindAProductResult
{
	/**
	 * Class constructor.
	 *
	 * @param ProductData $productData An instance of the PrintedProductData class.
	 */
	public function __construct(public ProductData $productData)
    {
    }
}
