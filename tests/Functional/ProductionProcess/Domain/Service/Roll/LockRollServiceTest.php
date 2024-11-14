<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Exceptions\LockingRollException;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\LockRollService;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class LockRollServiceTest extends AbstractTestCase
{
    use FixtureTools;
    use FakerTools;

    private LockRollService $lockRollService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->lockRollService = self::getContainer()->get(LockRollService::class);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    /**
     * @throws LockingRollException
     */
    public function test_it_locks_roll(): void
    {
        $roll = $this->loadRoll();
        $roll->setEmployeeId($this->loadUserFixture()->getId());

        $this->rollRepository->save($roll);

        $this->lockRollService->lock($roll->getId());

        $result = $this->rollRepository->findById($roll->getId());

        $this->assertTrue($result->isLocked());
    }

    /**
     * @throws LockingRollException
     */
    public function test_it_throws_exception_when_try_to_lock_already_locked_roll(): void
    {
        $roll = $this->loadRoll();
        $roll->setEmployeeId($this->loadUserFixture()->getId());
        $this->rollRepository->save($roll);

        $this->lockRollService->lock($roll->getId());

        $this->expectException(LockingRollException::class);

        $this->lockRollService->lock($roll->getId());
    }

    /**
     * @throws LockingRollException
     */
    public function test_it_throws_exception_when_no_employee_assigned(): void
    {
        $roll = $this->loadRoll();
        $rollId = $roll->getId();

        $this->expectException(LockingRollException::class);

        $this->lockRollService->lock($rollId);
    }

    /**
     * @throws LockingRollException
     */
    public function test_cant_lock_not_existing_roll(): void
    {
        $notExistingRollId = $this->getFaker()->randomNumber();

        $this->expectException(NotFoundHttpException::class);

        $this->lockRollService->lock($notExistingRollId);
    }
}
