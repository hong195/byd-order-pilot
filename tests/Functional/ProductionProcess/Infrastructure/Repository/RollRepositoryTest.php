<?php

namespace App\Tests\Functional\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Factory\RollFactory;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

class RollRepositoryTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;
    private RollRepositoryInterface $rollRepository;

    /**
     * Set up the test fixture before each test method is called.
     *
     * This method is called before each test method execution to set up the necessary resources and environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->rollRepository = $this->getContainer()->get(RollRepositoryInterface::class);
    }

    /**
     * Test the add method in the repository.
     *
     * This method tests the add functionality of the repository by creating two Error objects using ErrorFactory,
     * adding them to the repository, and then asserting that the added errors can be retrieved by the responsible employee ID.
     */
    public function test_can_save_successfully(): void
    {
        $roll = $this->loadRoll();

        $savedRoll = $this->rollRepository->findById($roll->getId());
        $this->assertSame($savedRoll->getId(), $roll->getId());
    }

    public function test_can_remove(): void
    {
        $roll = $this->loadRoll();

        $rollId = $roll->getId();

        $this->rollRepository->remove($roll);

        $removedRoll = $this->rollRepository->findById($rollId);

        $this->assertNull($removedRoll);
    }

    public function test_can_find_by_filter(): void
    {
        $fakeFilmId = $this->getFaker()->randomNumber();

        $roll1 = (new RollFactory())->create(
            name: $this->getFaker()->name(),
            filmId: $fakeFilmId,
            process: Process::GLOW_CHECK_IN,
        );

        $roll2 = (new RollFactory())->create(
            name: $this->getFaker()->name(),
            process: Process::CUTTING_CHECK_IN
        );

        $this->rollRepository->save($roll1);
        $this->rollRepository->save($roll2);

        $result = $this->rollRepository->findByFilter(new RollFilter(process: Process::GLOW_CHECK_IN, filmIds: [$fakeFilmId]));

        $this->assertCount(1, $result);
        $this->assertContains($roll1->getId(), array_map(fn ($roll) => $roll->getId(), $result));
        $this->assertNotContains($roll2->getId(), array_map(fn ($roll) => $roll->getId(), $result));
    }
}
