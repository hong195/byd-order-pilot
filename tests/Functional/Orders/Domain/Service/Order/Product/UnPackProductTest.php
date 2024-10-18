<?php

declare(strict_types=1);

namespace App\Tests\Functional\Orders\Domain\Service\Order\Product;

use App\Orders\Domain\Aggregate\Product;
use App\Orders\Domain\Exceptions\ProductPackException;
use App\Orders\Domain\Exceptions\ProductUnPackException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use App\Orders\Domain\Service\Order\Product\UnPackProduct;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FixtureTools;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UnPackProductTest extends AbstractTestCase
{
    use FixtureTools;
    private UnPackProduct $unpackProductService;

    private OrderRepositoryInterface $orderRepository;

    private ProductRepositoryInterface $productRepository;
    private EventDispatcher $eventDispatcherMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unpackProductService = self::getContainer()->get(UnPackProduct::class);
        $this->orderRepository = self::getContainer()->get(OrderRepositoryInterface::class);
        $this->productRepository = self::getContainer()->get(ProductRepositoryInterface::class);
    }

    /**
     * @throws ProductPackException
     */
    public function test_can_successfully_unpack_product(): void
    {
        $unpackService = $this->get_unpack_service();
        $FAKE_ORDER_ID = 999;
        $FAKE_PRODUCT_ID = 999;

        $this->expectException(NotFoundHttpException::class);

        $unpackService->handle(orderId: $FAKE_ORDER_ID, productId: $FAKE_PRODUCT_ID);
    }

    /**
     * @throws ProductUnPackException
     */
    public function test_cannot_unpack_if_product_or_order_dont_exists(): void
    {
        $unpackService = $this->get_unpack_service();
        $product = $this->get_product_for_testing();

        $unpackService->handle(orderId: $product->getOrder()->getId(), productId: $product->getId());

        $this->assertFalse($product->isPacked());
    }

    /**
     * @throws ProductUnPackException
     */
    public function test_cannot_unpack_product_if_its_already_unpacked(): void
    {
        $unpackService = $this->get_unpack_service();
        $product = $this->get_product_for_testing();

        $unpackService->handle(orderId: $product->getOrder()->getId(), productId: $product->getId());

        $this->expectException(ProductUnPackException::class);

        $unpackService->handle(orderId: $product->getOrder()->getId(), productId: $product->getId());
    }

    private function get_product_for_testing(): Product
    {
        $product = $this->loadProduct();
        $order = $this->loadOrder();
        $order->addProduct($product);
        $this->orderRepository->save($order);

        $product->pack();

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    private function get_unpack_service(): UnPackProduct
    {
        return $this->unpackProductService = new UnPackProduct(
            orderRepository: $this->orderRepository,
            productRepository: $this->productRepository,
        );
    }
}
