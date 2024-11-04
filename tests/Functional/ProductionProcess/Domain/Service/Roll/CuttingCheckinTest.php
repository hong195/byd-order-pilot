<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Events\RollWasSentToCutCheckInEvent;
use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToCuttingException;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\CuttingCheckInService;
use App\ProductionProcess\Domain\Service\Roll\GeneralProcessValidation;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class CuttingCheckinTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private CuttingCheckInService $checkInService;
    private RollRepositoryInterface $rollRepository;

    public function setUp(): void
    {
        parent::setUp();

        $eventDispatcher = self::createMock(EventDispatcherInterface::class);

        $this->checkInService = $this->getCuttingCheckinService($eventDispatcher);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    /**
     * @throws RollCantBeSentToCuttingException
     */
    public function test_can_successfully_send_to_cutting_checkin_process(): void
    {
        $roll = $this->loadPreparedRoll();
        $correctProcess = [Process::PRINTING_CHECK_IN, Process::GLOW_CHECK_IN];

        foreach ($correctProcess as $process) {
            $roll->updateProcess($process);

            $this->rollRepository->save($roll);

            $this->checkInService->handle($roll->getId());

            $this->assertTrue($roll->getProcess()->equals(Process::CUTTING_CHECK_IN));
        }
    }

    public function test_it_throws_exception_when_send_roll_to_cutting_checkin_process_from_incorrect_processes(): void
    {
        $roll = $this->loadPreparedRoll();
        $incorrectProcess = [Process::ORDER_CHECK_IN, Process::PRINTING_CHECK_IN, Process::CUTTING_CHECK_IN];

        foreach ($incorrectProcess as $process) {
            $roll->updateProcess($process);
            $this->rollRepository->save($roll);

            $this->expectException(RollCantBeSentToCuttingException::class);
            $this->checkInService->handle($roll->getId());
        }
    }

    /**
     * @throws RollCantBeSentToCuttingException
     */
    public function test_it_dispatches_event_after_sending_to_cutting_check_in(): void
    {
        $roll = $this->loadPreparedRoll();
        $roll->updateProcess(Process::PRINTING_CHECK_IN);
        $this->rollRepository->save($roll);

        $eventDispatcher = self::createMock(EventDispatcherInterface::class);
        $checkInService = $this->getCuttingCheckinService($eventDispatcher);

        $eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(RollWasSentToCutCheckInEvent::class));

        $checkInService->handle($roll->getId());
    }

    public function loadPreparedRoll(): Roll
    {
        $roll = $this->loadRoll();
        $roll->addPrintedProduct($this->loadPrintedProduct());
        $roll->setEmployeeId($this->loadUserFixture()->getId());
        $this->rollRepository->save($roll);

        return $roll;
    }

    private function getCuttingCheckinService(EventDispatcherInterface $eventDispatcher): CuttingCheckInService
    {
        return new CuttingCheckInService(
            self::getContainer()->get(RollRepositoryInterface::class),
            self::getContainer()->get(GeneralProcessValidation::class),
            $eventDispatcher
        );
    }
}
