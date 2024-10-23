<?php

declare(strict_types=1);

namespace App\Tests\Functional\Orders\Domain\Service\Order\Extra;

use App\Orders\Domain\Aggregate\Extra;
use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Service\Order\Extra\ExtraMaker;
use App\Orders\Domain\Service\Order\Extra\SetExtraPackStatusService;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

final class SetExtraPackStatusServiceTest extends AbstractTestCase
{
    use FixtureTools;
    use FakerTools;
    private OrderRepositoryInterface $orderRepository;

    private ExtraMaker $extraMaker;

    private Extra $extra1;
    private Extra $extra2;

    private SetExtraPackStatusService $extraPackStatusService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = self::getContainer()->get(OrderRepositoryInterface::class);
        $this->extraMaker = self::getContainer()->get(ExtraMaker::class);
        $this->extraPackStatusService = self::getContainer()->get(SetExtraPackStatusService::class);
    }

    public function test_can_successfully_pack_extra_status(): void
    {
        $order = $this->get_order_with_extras_for_testing();

        $this->extraPackStatusService->handle($order->getId(), $this->extra1->getId(), true);

        $this->assertTrue($this->extra1->isPacked());
        $this->assertFalse($this->extra2->isPacked());
    }

    public function test_can_successfully_unpack_extra_status(): void
    {
        $order = $this->get_order_with_extras_for_testing();

        $this->extraPackStatusService->handle($order->getId(), $this->extra2->getId(), false);

        $this->assertFalse($this->extra2->isPacked());
    }

    public function get_order_with_extras_for_testing(): Order
    {
        $order = $this->loadOrder();

        $this->extra1 = $this->extraMaker->make(
            name: $this->getFaker()->name(),
            orderNumber: $this->getFaker()->uuid(),
            count: $this->getFaker()->randomDigit()
        );

        $this->extra2 = $this->extraMaker->make(
            name: $this->getFaker()->name(),
            orderNumber: $this->getFaker()->uuid(),
            count: $this->getFaker()->randomDigit()
        );

        $order->addExtra($this->extra1);
        $order->addExtra($this->extra2);

        $this->orderRepository->save($order);

        return $order;
    }
}
