<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Exceptions\LockingRollException;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\UnLockRollService;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $roll->setEmployeeId($this->loadUserFixture()->getId());
        $roll->lock();
        $this->rollRepository->save($roll);

        $this->unlockRollService->unlock($roll->getId());

        $result = $this->rollRepository->findById($roll->getId());

        $this->assertTrue(!$result->isLocked());
    }

    /**
     * @throws LockingRollException
     */
    public function test_it_throws_exception_when_try_to_unlock_already_unlocked_roll(): void
    {
        $roll = $this->loadRoll();
        $roll->setEmployeeId($this->loadUserFixture()->getId());
        $this->rollRepository->save($roll);

        $this->expectException(LockingRollException::class);

        $this->unlockRollService->unlock($roll->getId());
    }

    /**
     * @throws LockingRollException
     */
    public function test_it_throws_exception_when_no_employee_assigned(): void
    {
        $roll = $this->loadRoll();

        $this->expectException(LockingRollException::class);

        $this->unlockRollService->unlock($roll->getId());
    }

    /**
     * @throws LockingRollException
     */
    public function test_cant_lock_not_existing_roll(): void
    {
        $notExistingRollId = $this->getFaker()->randomNumber();

        $this->expectException(NotFoundHttpException::class);

        $this->unlockRollService->unlock($notExistingRollId);
    }
}
