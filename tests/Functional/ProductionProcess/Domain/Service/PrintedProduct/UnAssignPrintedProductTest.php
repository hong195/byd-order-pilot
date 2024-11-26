<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Exceptions\UnassignedPrintedProductsException;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\UnAssignPrintedProduct;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Exception\DomainException;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UnAssignPrintedProductTest extends AbstractTestCase
{
    use FixtureTools;
    use FakerTools;

    private UnAssignPrintedProduct $unAssignPrintedProductService;

    private PrintedProductRepositoryInterface $printedProductRepository;
    private RollRepositoryInterface $rollRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unAssignPrintedProductService = self::getContainer()->get(UnAssignPrintedProduct::class);
        $this->printedProductRepository = self::getContainer()->get(PrintedProductRepositoryInterface::class);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    public function test_it_can_unassign_product(): void
    {
        $roll = $this->createPreparedRoll(filmType: $this->getFaker()->word(), productCount: 2);
        $product = $roll->getPrintedProducts()->first();

        $this->unAssignPrintedProductService->handle($product->getId());

        $refreshedProduct = $this->printedProductRepository->findById($product->getId());
        $refreshedRoll = $this->rollRepository->findById($roll->getId());

        $this->assertNull($refreshedProduct->getRoll());
        $this->assertNotContains($product->getId(), $refreshedRoll->getPrintedProducts()->map(fn ($p) => $p->getId())->toArray());
    }

    public function test_it_removes_roll_if_no_printed_products_left(): void
    {
        $roll = $this->createPreparedRoll(filmType: $this->getFaker()->word(), productCount: 1);
        $product = $roll->getPrintedProducts()->first();
        $rollId = $roll->getId();

        $this->unAssignPrintedProductService->handle($product->getId());

        $refreshedProduct = $this->printedProductRepository->findById($product->getId());
        $nullRoll = $this->rollRepository->findById($rollId);

        $this->assertNull($refreshedProduct->getRoll());
        $this->assertNull($nullRoll);
    }

    public function test_it_throws_exception_if_roll_no_found(): void
    {
        $roll = $this->createPreparedRoll(filmType: $this->getFaker()->word(), productCount: 1);
        $roll->updateProcess(Process::PRINTING_CHECK_IN);
        $this->rollRepository->save($roll);

        $product = $roll->getPrintedProducts()->first();

        $this->expectException(UnassignedPrintedProductsException::class);

        $this->unAssignPrintedProductService->handle($product->getId());
    }

    /**
     * @throws DomainException
     */
    public function test_it_throws_exception_if_product_not_found(): void
    {
        $fakeProductId = $this->getFaker()->randomDigit();

        $this->expectException(NotFoundHttpException::class);

        $this->unAssignPrintedProductService->handle($fakeProductId);
    }
}
