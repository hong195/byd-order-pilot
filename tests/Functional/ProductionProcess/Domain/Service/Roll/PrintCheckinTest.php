<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\DTO\FilmData;
use App\ProductionProcess\Domain\Events\RollWasSentToPrintCheckInEvent;
use App\ProductionProcess\Domain\Exceptions\InventoryFilmIsNotAvailableException;
use App\ProductionProcess\Domain\Exceptions\NotEnoughFilmLengthToPrintTheRollException;
use App\ProductionProcess\Domain\Exceptions\PrinterIsNotAvailableException;
use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToPrintException;
use App\ProductionProcess\Domain\Factory\PrintedProductFactory;
use App\ProductionProcess\Domain\Repository\PrinterRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Roll\GeneralProcessValidation;
use App\ProductionProcess\Domain\Service\Roll\PrintCheckInService;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PrintCheckinTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private RollRepositoryInterface $rollRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    /**
     * @throws NotEnoughFilmLengthToPrintTheRollException
     * @throws PrinterIsNotAvailableException
     * @throws RollCantBeSentToPrintException
     */
    public function test_can_successfully_send_to_print_check_in(): void
    {
        $roll = $this->loadPreparedRoll();

        $availableFilmService = $this->getAvailableServiceMock($roll, $roll->getPrintedProductsLength());
        $checkInService = $this->getPrintCheckinService($availableFilmService, self::createMock(EventDispatcherInterface::class));

        $checkInService->handle($roll->getId());

        $this->assertTrue($roll->getProcess()->equals(Process::PRINTING_CHECK_IN));
    }

    /**
     * @throws NotEnoughFilmLengthToPrintTheRollException
     * @throws PrinterIsNotAvailableException
     * @throws RollCantBeSentToPrintException
     */
    public function test_it_throws_exception_when_there_is_no_employee_assigned(): void
    {
        $roll = $this->loadRoll();
        $roll->updateProcess(Process::ORDER_CHECK_IN);
        $roll->addPrintedProduct($this->loadPreparedProduct('chrome'));
        $this->rollRepository->save($roll);

        $checkInService = $this->getPrintCheckinService(
            self::createMock(AvailableFilmServiceInterface::class),
            self::createMock(EventDispatcherInterface::class)
        );

        $this->assertNull($roll->getEmployeeId());
        $this->expectException(NotFoundHttpException::class);

        $checkInService->handle($roll->getId());
    }

    /**
     * @throws PrinterIsNotAvailableException
     * @throws RollCantBeSentToPrintException
     * @throws NotEnoughFilmLengthToPrintTheRollException
     */
    public function test_it_throws_exception_when_there_are_no_orders_on_the_roll(): void
    {
        $roll = $this->loadRoll();
        $roll->updateProcess(Process::ORDER_CHECK_IN);
        $this->rollRepository->save($roll);

        $checkInService = $this->getPrintCheckinService(
            self::createMock(AvailableFilmServiceInterface::class),
            self::createMock(EventDispatcherInterface::class)
        );

        $this->assertEmpty($roll->getPrintedProducts());
        $this->expectException(NotFoundHttpException::class);

        $checkInService->handle($roll->getId());
    }

    /**
     * @throws PrinterIsNotAvailableException
     * @throws RollCantBeSentToPrintException
     * @throws NotEnoughFilmLengthToPrintTheRollException
     */
    public function test_it_dispatches_event_after_sending_to_cutting_check_in(): void
    {
        $roll = $this->loadPreparedRoll();

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $checkInService = $this->getPrintCheckinService(
            $this->getAvailableServiceMock($roll, $roll->getPrintedProductsLength()),
            $eventDispatcher
        );

        $eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(RollWasSentToPrintCheckInEvent::class));

        $checkInService->handle($roll->getId());
    }

    public function loadPreparedRoll(): Roll
    {
        $printerRepository = self::getContainer()->get(PrinterRepositoryInterface::class);
        $roll = $this->loadRoll();
        $film = $this->loadInventoryFilm();
        $roll->setFilmId($film->getId());

        $roll->addPrintedProduct($this->loadPreparedProduct('chrome'));
        $roll->setEmployeeId($this->loadUserFixture()->getId());
        $roll->updateProcess(Process::ORDER_CHECK_IN);
        $roll->assignPrinter($printerRepository->all()->first());

        $this->entityManager->persist($roll);
        $this->entityManager->flush();

        return $roll;
    }

    private function loadPreparedProduct(string $filmType): PrintedProduct
    {
        $product = (new PrintedProductFactory())->make(
            relatedProductId: $this->getFaker()->randomNumber(),
            orderNumber: (string) $this->getFaker()->randomNumber(),
            filmType: $filmType,
            length: 1
        );

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    /**
     * @throws PrinterIsNotAvailableException
     * @throws RollCantBeSentToPrintException
     * @throws NotEnoughFilmLengthToPrintTheRollException
     * @throws InventoryFilmIsNotAvailableException
     */
    public function test_it_throws_exception_when_film_is_already_in_use()
    {
        $roll = $this->loadPreparedRoll();
        $roll->updateProcess(Process::ORDER_CHECK_IN);
        $this->rollRepository->save($roll);

        $rollWithOccupiedFilm = $this->loadPreparedRoll();
        $rollWithOccupiedFilm->updateProcess(Process::PRINTING_CHECK_IN);
        $rollWithOccupiedFilm->setFilmId($roll->getFilmId());
        $this->rollRepository->save($rollWithOccupiedFilm);

        $checkInService = $this->getPrintCheckinService(
            $this->getAvailableServiceMock($roll, $roll->getPrintedProductsLength()),
            self::createMock(EventDispatcherInterface::class)
        );

        $this->expectException(InventoryFilmIsNotAvailableException::class);

        $checkInService->handle($roll->getId());
    }

    private function getPrintCheckinService(AvailableFilmServiceInterface $availableFilmService, EventDispatcherInterface $eventDispatcher): PrintCheckInService
    {
        return new PrintCheckInService(
            rollRepository: self::getContainer()->get(RollRepositoryInterface::class),
            availableFilmService: $availableFilmService,
            eventDispatcher: $eventDispatcher,
            generalProcessValidatior: self::getContainer()->get(GeneralProcessValidation::class)
        );
    }

    private function getAvailableServiceMock(Roll $roll, float|int $length = 0): AvailableFilmServiceInterface
    {
        $availableFilmService = self::createMock(AvailableFilmServiceInterface::class);
        $availableFilmService->method('getAvailableFilms')
            ->willReturn(new ArrayCollection(
                [
                    new FilmData(
                        id: $roll->getFilmId(),
                        name: $this->getFaker()->name(),
                        length: $length,
                        filmType: $roll->getFilmTypes()[0]
                    ),
                ]
            ));

        return $availableFilmService;
    }
}
