<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use  App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\RollMaker;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

final class RollMakerTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private RollMaker $rollMaker;
    private RollRepositoryInterface $rollRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->rollMaker = self::getContainer()->get(RollMaker::class);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    public function test_it_can_make_roll(): void
    {
        $roll = $this->rollMaker->make(
            name: $this->getFaker()->name(),
            filmId: $this->getFaker()->randomNumber(),
        );

        $madeRoll = $this->rollRepository->findById($roll->getId());

        $this->assertInstanceOf(Roll::class, $roll);
        $this->assertSame($madeRoll->getId(), $roll->getId());
    }
}
