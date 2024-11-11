<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Exceptions\LockingRollException;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\UnLockRollService;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

final class UnLockRollServiceTest extends AbstractTestCase
{
    use FixtureTools;
    use FakerTools;

    private UnLockRollService $unlockRollService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unlockRollService = self::getContainer()->get(UnLockRollService::class);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    /**
     * @throws LockingRollException
     */
    public function test_it_unlocks_roll(): void
    {
        $roll = $this->loadRoll();
        $rollId = $roll->getId();
        $roll->lock();
        $this->rollRepository->save($roll);

        $this->unlockRollService->unlock($rollId);

        $result = $this->rollRepository->findById($rollId);

        $this->assertTrue(!$result->isLocked());
    }

    /**
     * @throws LockingRollException
     */
    public function test_it_throws_exception_when_try_to_unlock_already_unlocked_roll(): void
    {
        $roll = $this->loadRoll();
        $rollId = $roll->getId();

        $this->expectException(LockingRollException::class);

        $this->unlockRollService->unlock($rollId);
    }
}
