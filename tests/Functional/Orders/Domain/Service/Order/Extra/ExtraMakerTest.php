<?php

declare(strict_types=1);

namespace App\Tests\Functional\Orders\Domain\Service\Order\Extra;

use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Service\Order\Extra\ExtraMaker;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

final class ExtraMakerTest extends AbstractTestCase
{
    use FixtureTools;
    use FakerTools;
    private OrderRepositoryInterface $orderRepository;

    private ExtraMaker $extraMaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = self::getContainer()->get(OrderRepositoryInterface::class);
        $this->extraMaker = self::getContainer()->get(ExtraMaker::class);
    }

    /**
     * @throws \Exception
     */
    public function test_can_successfully_make_extra(): void
    {
        $order = $this->loadOrder();

        $extra = $this->extraMaker->make(
            name: $this->getFaker()->name(),
            orderNumber: $this->getFaker()->uuid(),
            count: $this->getFaker()->randomDigit()
        );

        $order->addExtra($extra);
        $this->orderRepository->save($order);

        $this->assertContains($extra, $order->getExtras());
    }
}
