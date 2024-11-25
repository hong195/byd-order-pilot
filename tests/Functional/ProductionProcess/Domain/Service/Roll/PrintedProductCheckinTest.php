<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use  App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto\AutoArrangePrintedProductsService;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

final class PrintedProductCheckinTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    public const FILM_TYPE = 'chrome';
    private AutoArrangePrintedProductsService $printedProductCheckIn;
    private RollRepositoryInterface $rollRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->printedProductCheckIn = self::getContainer()->get(AutoArrangePrintedProductsService::class);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    /**
     * @throws \Exception
     */
    public function test_can_allocate_group_of_printed_products_on_one_roll(): void
    {
        // TODO cover test cases
    }
}
