<?php

declare(strict_types=1);

namespace App\Tests\Functional\Orders\Domain\Service\Order\Product;

use App\Orders\Domain\Aggregate\Product;
use App\Orders\Domain\Event\ProductPackedEvent;
use App\Orders\Domain\Exceptions\CantPackMainProductException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use App\Orders\Domain\Service\Order\Product\CheckProductProcessInterface;
use App\Orders\Domain\Service\Order\Product\PackProduct;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FixtureTools;
use Symfony\Component\EventDispatcher\EventDispatcher;

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

        // disable the entity listener to avoid the event listener
        $this->entityManager->getConfiguration()->getEntityListenerResolver()->clear();

        $this->packProductService = self::getContainer()->get(PackProduct::class);
        $this->orderRepository = self::getContainer()->get(OrderRepositoryInterface::class);
        $this->productRepository = self::getContainer()->get(ProductRepositoryInterface::class);
        $this->eventDispatcherMock = $this->createMock(EventDispatcher::class);

        $this->eventDispatcherMock->method('dispatch')->willReturnCallback(
            function ($event) {
                return $event;
            });
    }

    /**
     * @throws CantPackMainProductException
     */
    public function test_can_successfully_pack_product(): void
    {
        $checkProductProcess = $this->createMock(CheckProductProcessInterface::class);
        $checkProductProcess->method('canPack')->willReturn(true);

        $packService = $this->get_pack_service($checkProductProcess);
        $product = $this->prepare_product_for_testing();

        $packService->handle(orderId: $product->getOrder()->getId(), productId: $product->getId());

        $this->assertTrue($product->isPacked());
    }

    /**
     * @throws CantPackMainProductException
     */
    public function test_cant_pack_product_if_its_already_packed(): void
    {
        $checkProductProcess = $this->createMock(CheckProductProcessInterface::class);
        $checkProductProcess->method('canPack')->willReturn(true);

        $packService = $this->get_pack_service($checkProductProcess);
        $product = $this->prepare_product_for_testing();

        $packService->handle(orderId: $product->getOrder()->getId(), productId: $product->getId());

        $this->assertTrue($product->isPacked());

        $this->expectException(CantPackMainProductException::class);

        $packService->handle(orderId: $product->getOrder()->getId(), productId: $product->getId());
    }

    /**
     * @throws CantPackMainProductException
     */
    public function test_cant_pack_product_if_its_not_ready_for_packing(): void
    {
        $checkProductProcess = $this->createMock(CheckProductProcessInterface::class);

        $checkProductProcess->method('canPack')->willReturn(false);

        $packService = $this->get_pack_service($checkProductProcess);
        $product = $this->prepare_product_for_testing();

        $this->expectException(CantPackMainProductException::class);

        $packService->handle(orderId: $product->getOrder()->getId(), productId: $product->getId());
    }

    /**
     * @throws CantPackMainProductException
     */
    public function test_if_product_packed_product_pack_event_dispatched(): void
    {
        $checkProductProcess = $this->createMock(CheckProductProcessInterface::class);
        $checkProductProcess->method('canPack')->willReturn(true);

        $packService = $this->get_pack_service($checkProductProcess);
        $product = $this->prepare_product_for_testing();

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(ProductPackedEvent::class));

        $packService->handle(orderId: $product->getOrder()->getId(), productId: $product->getId());
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
            orderRepository: $this->orderRepository,
            productRepository: $this->productRepository,
            checkProductProcess: $checkProductProcess,
            eventDispatcher: $this->eventDispatcherMock
        );
    }
}
