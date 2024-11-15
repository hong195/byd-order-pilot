<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Printer;

use App\ProductionProcess\Domain\Aggregate\Printer\Printer;
use App\ProductionProcess\Domain\Repository\ConditionRepositoryInterface;
use App\ProductionProcess\Domain\Repository\PrinterRepositoryInterface;
use App\ProductionProcess\Domain\Service\Printer\InitPrintersService;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Doctrine\Common\Collections\Collection;

final class InitPrintersServiceTest extends AbstractTestCase
{
    use FixtureTools;
    use FakerTools;

    private InitPrintersService $initPrintersService;
    private PrinterRepositoryInterface $printerRepository;
    private ConditionRepositoryInterface $conditionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->initPrintersService = self::getContainer()->get(InitPrintersService::class);
        $this->printersRepository = self::getContainer()->get(PrinterRepositoryInterface::class);
        $this->conditionRepository = self::getContainer()->get(ConditionRepositoryInterface::class);
    }

    public function test_it_creates_printers(): void
    {
        /**
         * @var Collection<Printer> $printers
         */
        $printers = $this->printersRepository->all();
        $conditions = $this->conditionRepository->all();

        $this->assertNotEmpty($printers);
        $this->assertNotEmpty($conditions);
    }
}
