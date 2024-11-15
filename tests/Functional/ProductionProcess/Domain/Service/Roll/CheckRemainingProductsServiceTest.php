<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Repository\Printer\PrinterRepositoryInterface;
use  App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\CheckRemainingProductsService;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

final class CheckRemainingProductsServiceTest extends AbstractTestCase
{
    use FixtureTools;
    use FakerTools;

    private CheckRemainingProductsService $checkRemainingProductsService;
    private RollRepositoryInterface $rollRepository;
    private PrinterRepositoryInterface $printerRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
        $this->printerRepository = self::getContainer()->get(PrinterRepositoryInterface::class);

        $this->checkRemainingProductsService = self::getContainer()->get(CheckRemainingProductsService::class);
    }

    public function test_it_removes_roll_if_there_are_no_printed_product_left(): void
    {
        $roll = $this->loadRoll();
        $rollId = $roll->getId();

        $this->checkRemainingProductsService->check($rollId);

        $result = $this->rollRepository->findById($rollId);

        $this->assertNull($result);
    }

    public function test_printer_become_available_if_last_element_was_removed(): void
    {
        $roll = $this->loadRoll();
        $roll->addPrintedProduct($this->loadPrintedProduct());
        $rollId = $roll->getId();
        $printer = $this->printerRepository->all()->first();
        $roll->assignPrinter($printer);
        $this->rollRepository->save($roll);

        $this->checkRemainingProductsService->check($rollId);

        $this->assertTrue($printer->isAvailable());
    }

    public function test_it_throws_exception_if_roll_does_not_exists(): void
    {
        $roll = $this->loadRoll();
        $rollId = $roll->getId();

        $this->rollRepository->remove($roll);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $this->checkRemainingProductsService->check($rollId);
    }
}
