<?php

namespace App\Tests\Functional\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Customer;
use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Factory\OrderFactory;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Service\Order\Extra\ExtraMaker;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

class OrderRepositoryTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private OrderRepositoryInterface $orderRepository;

    /**
     * Set up the test fixture before each test method is called.
     *
     * This method is called before each test method execution to set up the necessary resources and environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = $this->getContainer()->get(OrderRepositoryInterface::class);
        $this->entityManager->clear();
    }

    public function test_can_successfully_save(): void
    {
        $order = (new OrderFactory())
                ->make(
                    customer: new Customer(
                        name: $this->getFaker()->name(),
                        notes: $this->getFaker()->text()
                    ),
                    shippingAddress: $this->getFaker()->address(),
                    orderNumber: $this->getFaker()->word(),
                );

        $this->orderRepository->save($order);

        $this->assertNotNull($order->getId());
    }

    public function test_can_find_packed(): void
    {
        $readyForPackOrder = $this->get_ready_for_pack_order();
        $partiallyPacked = $this->get_partially_packed_order();
        $notReadyForPackOrder = $this->get_not_ready_for_pack_order();

        $results = $this->orderRepository->findPacked();

        $this->assertCount(count([$readyForPackOrder]), $results);
        $this->assertContains($readyForPackOrder->getId(), array_map(fn ($order) => $order->getId(), $results));
        $this->assertNotContains($partiallyPacked->getId(), array_map(fn ($order) => $order->getId(), $results));
        $this->assertNotContains($notReadyForPackOrder->getId(), array_map(fn ($order) => $order->getId(), $results));
    }

    public function test_can_find_partially_packed(): void
    {
		$readyForPackOrder = $this->get_ready_for_pack_order();
		$partiallyPacked = $this->get_partially_packed_order();
		$notReadyForPackOrder = $this->get_not_ready_for_pack_order();

        $results = $this->orderRepository->findPartiallyPacked();

        $this->assertCount(count([$partiallyPacked, $notReadyForPackOrder]), $results);
        $this->assertContains($partiallyPacked->getId(), array_map(fn (Order $order) => $order->getId(), $results));
        $this->assertContains($notReadyForPackOrder->getId(), array_map(fn (Order $order) => $order->getId(), $results));
        $this->assertNotContains($readyForPackOrder->getId(), array_map(fn (Order $order) => $order->getId(), $results));
    }

    public function test_can_find_only_with_extras(): void
    {
        $orderWithExtras = $this->get_product_with_extras();
        $partiallyPackedOrder = $this->get_partially_packed_order();
        $packedOrder = $this->get_ready_for_pack_order();

        $results = $this->orderRepository->findOnlyWithExtras();

        $this->assertCount(count([$orderWithExtras]), $results);
        $this->assertContains($orderWithExtras->getId(), array_map(fn (Order $order) => $order->getId(), $results));
        $this->assertNotContains($partiallyPackedOrder->getId(), array_map(fn (Order $order) => $order->getId(), $results));
        $this->assertNotContains($packedOrder->getId(), array_map(fn (Order $order) => $order->getId(), $results));
    }

    public function get_product_with_extras(): Order
    {
        $order = $this->loadOrder();

        /** @var ExtraMaker $extraMaker */
        $extraMaker = self::getContainer()->get(ExtraMaker::class);

        for ($i = 0; $i < 2; ++$i) {
            $extra = $extraMaker->make(
                name: $this->getFaker()->word(),
                orderNumber: $this->getFaker()->word(),
                count: $this->getFaker()->numberBetween(1, 10),
            );

            $order->addExtra($extra);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    private function get_ready_for_pack_order(): Order
    {
        $order = $this->loadOrder();

        $product1 = $this->loadProduct();
        $product2 = $this->loadProduct();

        $product1->pack();
        $product2->pack();

        $order->addProduct($product1);
        $order->addProduct($product2);

        $this->entityManager->persist($product1);
        $this->entityManager->persist($product2);
        $this->entityManager->persist($order);

        $this->entityManager->flush();

        return $order;
    }

    private function get_not_ready_for_pack_order(): Order
    {
        $order = $this->loadOrder();

        $product = $this->loadProduct();
        $product1 = $this->loadProduct();

        $order->addProduct($product);
        $order->addProduct($product1);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

	private function get_partially_packed_order(): Order
	{
		$order = $this->loadOrder();

		$product = $this->loadProduct();
		$product1 = $this->loadProduct();

		$product->pack();

		$order->addProduct($product);
		$order->addProduct($product1);

		$this->entityManager->persist($order);
		$this->entityManager->flush();

		return $order;
	}
}
