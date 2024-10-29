<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Factory\PrintedProductFactory;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Printer\InitPrintersService;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\PrintedProductsCheckInService;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

final class PrintedProductCheckinTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    public const FILM_TYPE = 'chrome';
    private PrintedProductsCheckInService $printedProductCheckIn;
    private RollRepositoryInterface $rollRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->printedProductCheckIn = self::getContainer()->get(PrintedProductsCheckInService::class);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);

        $this->initPrinters();
    }

    private function initPrinters(): void
    {
        /** @var InitPrintersService $initPrintersService */
        $initPrintersService = self::getContainer()->get(InitPrintersService::class);

        $initPrintersService->init();
    }

    /**
     * @throws \Exception
     */
    public function test_can_allocate_group_of_printed_products_on_one_roll(): void
    {
        $orderNumber = $this->getFaker()->word();

        $printedProduct1 = (new PrintedProductFactory())
            ->make(
                relatedProductId: $this->getFaker()->randomDigit(),
                orderNumber: $orderNumber,
                filmType: self::FILM_TYPE,
                length: 10
            );

        $printedProduct2 = (new PrintedProductFactory())
            ->make(
                relatedProductId: $this->getFaker()->randomDigit(),
                orderNumber: $orderNumber,
                filmType: self::FILM_TYPE,
                length: 10
            );

        $this->entityManager->persist($printedProduct1);
        $this->entityManager->persist($printedProduct2);

        $this->entityManager->flush();

        $this->printedProductCheckIn->arrange();

        $roll = $this->rollRepository->findByFilter(new RollFilter(process: Process::ORDER_CHECK_IN));

        $this->assertCount(1, $roll);
    }
}
