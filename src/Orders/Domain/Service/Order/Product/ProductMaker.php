<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order\Product;

use App\Orders\Domain\Aggregate\Product;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * A final readonly class responsible for creating products.
 */
final readonly class ProductMaker
{
    /**
     * Class constructor.
     *
     * @param OrderRepositoryInterface   $orderRepository   the order repository
     * @param ProductRepositoryInterface $productRepository the product repository
     */
    public function __construct(private OrderRepositoryInterface $orderRepository, private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * Makes a new product for the given order.
     *
     * @param int                 $orderId        the ID of the order
     * @param FilmType            $filmType       the film type of the product
     * @param LaminationType|null $laminationType the lamination type of the product (optional)
     *
     * @return int the ID of the created product
     *
     * @throws NotFoundHttpException if the order is not found
     */
    public function make(int $orderId, FilmType $filmType, ?LaminationType $laminationType = null): int
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        $product = new Product($filmType, $laminationType);

        $this->productRepository->save($product);

        $order->addProduct($product);

        $this->orderRepository->save($order);

        return $product->getId();
    }
}
