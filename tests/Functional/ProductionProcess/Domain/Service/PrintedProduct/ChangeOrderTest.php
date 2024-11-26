<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\ChangeSortOrder;
use App\Shared\Infrastructure\EventListener\Doctrine\PublishDomainEventsOnFlushListener;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Doctrine\ORM\Events;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ChangeOrderTest extends AbstractTestCase
{
    use FixtureTools;
    use FakerTools;

    private ChangeSortOrder $changeSortOrder;
    private PrintedProductRepositoryInterface $printedProductRepository;
    private RollRepositoryInterface $rollRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // turn off doctrine event listener
        $this->entityManager->getEventManager()->removeEventListener(Events::onFlush, $this->getContainer()->get(PublishDomainEventsOnFlushListener::class));

        $this->changeSortOrder = self::getContainer()->get(ChangeSortOrder::class);

        $this->printedProductRepository = self::getContainer()->get(PrintedProductRepositoryInterface::class);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    public function test_can_change_product_sort_order(): void
    {
        $roll = $this->createPreparedRoll(filmType: $this->getFaker()->word(), productCount: 3);
        $firstProduct = $roll->getPrintedProducts()->first();
        $secondProduct = $roll->getPrintedProducts()->next();
        $thirdProduct = $roll->getPrintedProducts()->next();

        $this->changeSortOrder->handle($roll->getId(), 0, [
            $firstProduct->getId() => 3,
            $secondProduct->getId() => 1,
            $thirdProduct->getId() => 2,
        ]);

        $this->assertTrue(3 === $firstProduct->getSortOrder());
        $this->assertTrue(1 === $secondProduct->getSortOrder());
        $this->assertTrue(2 === $thirdProduct->getSortOrder());
    }

    public function test_it_throws_exception_if_roll_not_found(): void
    {
        $product = $this->loadPrintedProduct();
        $product2 = $this->loadPrintedProduct();
        $notExistingRollId = 0;

        $this->expectException(NotFoundHttpException::class);

        $this->changeSortOrder->handle($notExistingRollId, 0, [
            $product->getId() => 3,
            $product2->getId() => 1,
        ]);
    }

    public function test_it_throws_exception_if_group_not_found(): void
    {
        $roll = $this->createPreparedRoll(filmType: $this->getFaker()->word(), productCount: 2);
        $firstProduct = $roll->getPrintedProducts()->first();
        $secondProduct = $roll->getPrintedProducts()->next();

        $notExistingGroup = 1_000;

        $this->expectException(NotFoundHttpException::class);

        $this->changeSortOrder->handle($roll->getId(), $notExistingGroup, [
            $firstProduct->getId() => 2,
            $secondProduct->getId() => 1,
        ]);
    }
}
