<?php

declare(strict_types=1);

namespace App\Tests\Functional\Orders\Domain\Service\Order\Product;

use App\Orders\Domain\Exceptions\CantPackMainProductException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Service\Order\Product\PackProduct;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FixtureTools;

final class PackProductTest extends AbstractTestCase
{
    use FixtureTools;
    private PackProduct $packProductService;

    private OrderRepositoryInterface $orderRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // disable the entity listener to avoid the event listener
        $this->entityManager->getConfiguration()->getEntityListenerResolver()->clear();

        $this->packProductService = self::getContainer()->get(PackProduct::class)
			->setParameter();
        $this->orderRepository = self::getContainer()->get(OrderRepositoryInterface::class);
    }

    /**
     * @throws CantPackMainProductException
     */
    public function test_can_successfully_pack_product()
    {
        $product = $this->loadProduct();
        $order = $this->loadOrder();

        $order->addProduct($product);
        $this->orderRepository->save($order);

        $this->packProductService->handle(orderId: $order->getId(), productId: $product->getId());

        $this->assertTrue($product->isPacked());
    }
}
