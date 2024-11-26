<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\Error\ErrorManagementServiceInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\ReprintPrintedProduct;
use App\ProductionProcess\Domain\Service\Roll\CheckRemainingProductsService;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Security\UserFetcherInterface;
use App\Shared\Infrastructure\EventListener\Doctrine\PublishDomainEventsOnFlushListener;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Doctrine\ORM\Events;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ReprintPrintedProductTest extends AbstractTestCase
{
    use FixtureTools;
    use FakerTools;

    private ReprintPrintedProduct $reprintService;
    private PrintedProductRepositoryInterface $printedProductRepository;
    private RollRepositoryInterface $rollRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // turn off doctrine event listener
        $this->entityManager->getEventManager()->removeEventListener(Events::onFlush, $this->getContainer()->get(PublishDomainEventsOnFlushListener::class));

        $userFetcherMock = $this->createMock(UserFetcherInterface::class);
        $userFetcherMock->method('requiredUserId')->willReturn($this->getFaker()->numberBetween(1, 100));

        $errorManagementMock = $this->createMock(ErrorManagementServiceInterface::class);

        $this->reprintService = new ReprintPrintedProduct(
            $this->getContainer()->get(PrintedProductRepositoryInterface::class),
            $errorManagementMock,
            $userFetcherMock,
            self::getContainer()->get(RollRepositoryInterface::class),
            self::getContainer()->get(CheckRemainingProductsService::class)
        );

        $this->printedProductRepository = self::getContainer()->get(PrintedProductRepositoryInterface::class);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    public function test_it_can_reprint_printed_product(): void
    {
        $roll = $this->createPreparedRoll(filmType: $this->getFaker()->word(), productCount: 2);
        $roll->setEmployeeId($this->getFaker()->numberBetween(1, 100));
        $this->rollRepository->save($roll);

        $printedProduct = $roll->getPrintedProducts()->first();

        $this->reprintService->handle($printedProduct->getId(), Process::ORDER_CHECK_IN);

        $reprintedProduct = $this->printedProductRepository->findById($printedProduct->getId());

        $this->assertTrue($reprintedProduct->isReprint());
        $this->assertNull($reprintedProduct->getRoll());
        $this->assertNotContains($reprintedProduct->getId(), $roll->getPrintedProducts()->map(fn (PrintedProduct $p) => $p->getId())->toArray());
    }

    public function test_it_throws_exception_if_no_product_found(): void
    {
        $fakePrintedProductId = $this->getFaker()->numberBetween(1, 100);
        $this->expectException(NotFoundHttpException::class);

        $this->reprintService->handle($fakePrintedProductId, Process::ORDER_CHECK_IN);
    }

    public function test_it_throws_exception_if_product_is_not_assigned_to_a_roll(): void
    {
        $productWithoutRoll = $this->loadPrintedProduct();

        $this->expectException(NotFoundHttpException::class);
        $this->assertNull($productWithoutRoll->getRoll());

        $this->reprintService->handle($productWithoutRoll->getId(), Process::ORDER_CHECK_IN);
    }

    public function test_it_throws_exception_if_no_employee_assigned(): void
    {
        $roll = $this->createPreparedRoll(filmType: $this->getFaker()->word(), productCount: 2);
        $product = $roll->getPrintedProducts()->first();

        $this->expectException(NotFoundHttpException::class);
        $this->assertNull($roll->getEmployeeId());

        $this->reprintService->handle($product->getId(), Process::ORDER_CHECK_IN);
    }

    public function test_it_removes_roll_if_last_product_was_sent_to_reprint(): void
    {
        $roll = $this->createPreparedRoll(filmType: $this->getFaker()->word(), productCount: 1);
        $roll->setEmployeeId($this->getFaker()->numberBetween(1, 100));
        $this->rollRepository->save($roll);
        $product = $roll->getPrintedProducts()->first();
        $rollId = $roll->getId();

        $this->reprintService->handle($product->getId(), Process::ORDER_CHECK_IN);

        $refechedRoll = $this->rollRepository->findById($rollId);
        $fetchedProduct = $this->printedProductRepository->findById($product->getId());

        $this->assertTrue($fetchedProduct->isReprint());
        $this->assertNull($refechedRoll);
        $this->assertNull($fetchedProduct->getRoll());
    }
}
