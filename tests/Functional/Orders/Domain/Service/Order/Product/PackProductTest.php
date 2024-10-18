<?php

declare(strict_types=1);

namespace App\Tests\Functional\Orders\Domain\Service\Order\Product;

use App\Orders\Domain\Aggregate\Product;
use App\Orders\Domain\Exceptions\ProductPackException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use App\Orders\Domain\Service\Order\Product\CheckProductProcessInterface;
use App\Orders\Domain\Service\Order\Product\PackProduct;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FixtureTools;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PackProductTest extends AbstractTestCase
{
    use FixtureTools;
    private PackProduct $packProductService;

    private OrderRepositoryInterface $orderRepository;

    private ProductRepositoryInterface $productRepository;
    private EventDispatcher $eventDispatcherMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->packProductService = self::getContainer()->get(PackProduct::class);
        $this->orderRepository = self::getContainer()->get(OrderRepositoryInterface::class);
        $this->productRepository = self::getContainer()->get(ProductRepositoryInterface::class);
    }

    /**
     * @throws ProductPackException
     */
    public function test_can_successfully_pack_product(): void
    {
        $checkProductProcess = $this->createMock(CheckProductProcessInterface::class);
        $checkProductProcess->method('canPack')->willReturn(true);

        $packService = $this->get_pack_service($checkProductProcess);
        $product = $this->prepare_product_for_testing();

        $packService->handle(productId: $product->getId());

        $this->assertTrue($product->isPacked());
    }

    public function test_cant_pack_product_if_product_does_not_exist(): void
    {
        $FAKE_PRODUCT_ID = 999;

        $packService = self::getContainer()->get(PackProduct::class);

        $this->expectException(NotFoundHttpException::class);

        $packService->handle(productId: $FAKE_PRODUCT_ID);
    }

    /**
     * @throws ProductPackException
     */
    public function test_cant_pack_product_if_its_already_packed(): void
    {
        $checkProductProcess = $this->createMock(CheckProductProcessInterface::class);
        $checkProductProcess->method('canPack')->willReturn(true);

        $packService = $this->get_pack_service($checkProductProcess);
        $product = $this->prepare_product_for_testing();

        $packService->handle(productId: $product->getId());

        $this->assertTrue($product->isPacked());

        $this->expectException(ProductPackException::class);

        $packService->handle(productId: $product->getId());
    }

    /**
     * @throws ProductPackException
     */
    public function test_cant_pack_product_if_its_not_ready_for_packing(): void
    {
        $checkProductProcess = $this->createMock(CheckProductProcessInterface::class);

        $checkProductProcess->method('canPack')->willReturn(false);

        $packService = $this->get_pack_service($checkProductProcess);
        $product = $this->prepare_product_for_testing();

        $this->expectException(ProductPackException::class);

        $packService->handle(productId: $product->getId());
    }

    private function prepare_product_for_testing(): Product
    {
        $product = $this->loadProduct();
        $order = $this->loadOrder();
        $order->addProduct($product);
        $this->orderRepository->save($order);

        return $product;
    }

    private function get_pack_service(CheckProductProcessInterface $checkProductProcess): PackProduct
    {
        return $this->packProductService = new PackProduct(
            productRepository: $this->productRepository,
            checkProductProcess: $checkProductProcess
        );
    }
}
