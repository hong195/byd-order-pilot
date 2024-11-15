<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Events\RollsWereSentToGlowCheckInEvent;
use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToGlowException;
use App\ProductionProcess\Domain\Factory\PrintedProductFactory;
use App\ProductionProcess\Domain\Repository\Printer\PrinterRepositoryInterface;
use  App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\GroupService;
use App\ProductionProcess\Domain\Service\Roll\GeneralProcessValidation;
use App\ProductionProcess\Domain\Service\Roll\GlowCheckInService;
use App\ProductionProcess\Domain\Service\Roll\RollMaker;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class GlowCheckinTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private GlowCheckInService $checkInService;
    private RollRepositoryInterface $rollRepository;

    public function setUp(): void
    {
        parent::setUp();

        $eventDispatcher = self::createMock(EventDispatcherInterface::class);

        $this->checkInService = $this->getGlowCheckinService($eventDispatcher);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    /**
     * @throws RollCantBeSentToGlowException
     */
    public function test_can_successfully_send_to_glow_checkin_process(): void
    {
        $roll = $this->loadPreparedRoll();
        $roll->addPrintedProduct($this->loadPreparedProduct('matt'));
        $this->rollRepository->save($roll);

        $this->checkInService->handle($roll->getId());

        $this->assertTrue($roll->getProcess()->equals(Process::GLOW_CHECK_IN));
    }

    /**
     * @throws RollCantBeSentToGlowException
     */
    public function test_roll_creation_match_lamination_types(): void
    {
        $roll = $this->loadPreparedRoll();
        $productWithMattGlow = $this->loadPreparedProduct('matt');
        $productWithGlossyGlow = $this->loadPreparedProduct('glossy');

        $roll->addPrintedProduct($productWithMattGlow);
        $roll->addPrintedProduct($productWithGlossyGlow);
        $this->rollRepository->save($roll);

        $this->checkInService->handle($roll->getId());
        $rollWithGlossyGlow = $productWithGlossyGlow->getRoll();
        $rollWithMattGlow = $productWithMattGlow->getRoll();

        $this->assertNotEmpty($rollWithGlossyGlow);
        $this->assertNotEmpty($rollWithMattGlow);
        $this->assertEmpty($roll->getPrintedProducts());
        $this->assertEquals(Process::GLOW_CHECK_IN, $rollWithGlossyGlow->getProcess());
        $this->assertEquals(Process::GLOW_CHECK_IN, $rollWithMattGlow->getProcess());
    }

    /**
     * @throws RollCantBeSentToGlowException
     */
    public function test_roll_can_be_send_directly_to_cutting_check_in_if_there_is_no_glow(): void
    {
        $roll = $this->loadPreparedRoll();
        $productWithMattGlow = $this->loadPreparedProduct(null);
        $roll->addPrintedProduct($productWithMattGlow);
        $this->rollRepository->save($roll);

        $this->checkInService->handle($roll->getId());

        $this->assertTrue($roll->getProcess()->equals(Process::CUTTING_CHECK_IN));
    }

    /**
     * @throws RollCantBeSentToGlowException
     */
    public function test_it_dispatches_event_after_sending_to_glow_check_in(): void
    {
        $roll = $this->loadPreparedRoll();
        $roll->addPrintedProduct($this->loadPreparedProduct('matt'));
        $this->rollRepository->save($roll);

        $eventDispatcher = self::createMock(EventDispatcherInterface::class);
        $checkInService = $this->getGlowCheckinService($eventDispatcher);

        $eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(RollsWereSentToGlowCheckInEvent::class));

        $checkInService->handle($roll->getId());
    }

    private function loadPreparedRoll(): Roll
    {
        $printerRepo = self::getContainer()->get(PrinterRepositoryInterface::class);
        $roll = $this->loadRoll();
        $printer = $printerRepo->all()->first();
        $roll->assignPrinter($printer);
        $roll->updateProcess(Process::PRINTING_CHECK_IN);
        $roll->setEmployeeId($this->loadUserFixture()->getId());

        $this->entityManager->persist($printer); // сохраняем Printer
        $this->entityManager->persist($roll);
        $this->entityManager->flush();

        return $roll;
    }

    private function loadPreparedProduct(?string $lamination = null): PrintedProduct
    {
        $product = (new PrintedProductFactory())->make(
            relatedProductId: $this->getFaker()->randomDigit(),
            orderNumber: (string) $this->getFaker()->randomDigit(),
            filmType: 'chrome',
            length: 1
        );

        if (null !== $lamination) {
            $product->setLaminationType($lamination);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    private function getGlowCheckinService(EventDispatcherInterface $eventDispatcher): GlowCheckInService
    {
        return new GlowCheckInService(
            rollRepository: self::getContainer()->get(RollRepositoryInterface::class),
            groupService: self::getContainer()->get(GroupService::class),
            rollMaker: self::getContainer()->get(RollMaker::class),
            eventDispatcher: $eventDispatcher,
            generalProcessValidatior: self::getContainer()->get(GeneralProcessValidation::class),
        );
    }
}
