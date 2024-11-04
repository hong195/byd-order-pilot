<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Repository\PrinterRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\RollMaker;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

final class RollMakerTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private RollMaker $rollMaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->rollMaker = self::getContainer()->get(RollMaker::class);
    }

    /**
     * @throws \Exception
     */
    public function test_it_can_make_roll(): void
    {
        $filmType = 'chrome';
        $roll = $this->rollMaker->make(
            name: $this->getFaker()->name(),
            filmId: $this->getFaker()->randomNumber(),
            filmType: $filmType
        );
        $printer = self::getContainer()->get(PrinterRepositoryInterface::class)->findByFilmType($filmType);

        $this->assertInstanceOf(Roll::class, $roll);
        $this->assertEquals($roll->getPrinter()->getId(), $printer->getId());
    }
}
