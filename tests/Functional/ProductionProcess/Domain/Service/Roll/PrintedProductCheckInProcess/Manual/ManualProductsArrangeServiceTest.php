<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual;

use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual\ManualProductsArrangeService;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

class ManualProductsArrangeServiceTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private RollRepositoryInterface $rollRepository;

    private ManualProductsArrangeService $manualProductsArrangeService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rollRepository = $this->getContainer()->get(RollRepositoryInterface::class);
        $this->manualProductsArrangeService = $this->getContainer()->get(ManualProductsArrangeService::class);
    }

    public function test_it_throws_exception_when_printed_products_have_different_film_type(): void
    {
    }

    public function test_it_throws_exception_when_printed_products_handled_by_different_printers(): void
    {
    }

    public function test_it_throws_exception_when_there_is_not_enough_film_to_print_printed_products(): void
    {
    }

    public function test_it_successfully_manually_arrange_printed_products_on_locked_roll(): void
    {
    }
}
