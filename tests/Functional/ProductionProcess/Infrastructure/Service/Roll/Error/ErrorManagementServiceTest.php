<?php

namespace App\Tests\Unit\ProductionProcess\Domain\Service\Roll\Error;

use App\ProductionProcess\Domain\Aggregate\Error;
use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\History\Type;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Exceptions\RollErrorManagementException;
use App\ProductionProcess\Domain\Factory\HistoryFactory;
use App\ProductionProcess\Domain\Repository\ErrorRepositoryInterface;
use App\ProductionProcess\Domain\Repository\HistoryRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\Error\ErrorManagementService;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FixtureTools;
use App\Users\Domain\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ErrorManagementServiceTest.
 */
class ErrorManagementServiceTest extends AbstractTestCase
{
    use FixtureTools;

    private User $responsibleEmployee;

    private User $noticer;

    private PrintedProduct $printedProduct;
    private Roll $roll;
    public const FAKE_PRINTED_PRODUCT = 1;
    public const FAKE_ROLL_ID = 1;
    private ErrorManagementService $errorManagementService;
    private HistoryRepositoryInterface $historyRepository;
    private ErrorRepositoryInterface $errorRepository;

    /**
     * Set up the test fixture before each test method is called.
     *
     * This method is called before each test method execution to set up the necessary resources and environment.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        parent::setUp();

        $this->errorManagementService = $this->getContainer()->get(ErrorManagementService::class);
        $this->errorRepository = $this->getContainer()->get(ErrorRepositoryInterface::class);
        $this->historyRepository = $this->getContainer()->get(HistoryRepositoryInterface::class);

        $this->responsibleEmployee = $this->loadUserFixture();
        $this->noticer = $this->loadUserFixture();
        $this->printedProduct = $this->loadPrintedProduct();
        $this->roll = $this->loadRoll();

        $this->roll->addPrintedProduct($this->printedProduct);

        $this->entityManager->persist($this->roll);
        $this->entityManager->flush();
    }

    /**
     * Test if errors can be recorded successfully.
     *
     * @throws RollErrorManagementException
     */
    public function test_can_be_recorded(): void
    {
        $this->createHistory();

        $this->errorManagementService->recordError(
            printedProductId: $this->printedProduct->getId(),
            process: Process::CUTTING_CHECK_IN,
            noticerId: $this->noticer->getId(),
            message: 'This is a test error message'
        );

        $errors = $this->errorRepository->findByResponsibleEmployeeId($this->responsibleEmployee->getId());

        $this->assertCount(1, $errors);

        /** @var Error $error */
        foreach ($errors as $error) {
            $this->assertEquals($this->printedProduct->getId(), $error->printedProductId);
            $this->assertEquals(Process::CUTTING_CHECK_IN, $error->process);
            $this->assertEquals($this->responsibleEmployee->getId(), $error->responsibleEmployeeId);
            $this->assertEquals($this->noticer->getId(), $error->noticerId);
        }
    }

    /**
     * Test that an error can be recorded on the current or previous process for a roll with existing history.
     *
     * @throws RollErrorManagementException
     */
    public function test_error_can_be_record_on_current_or_previous_process(): void
    {
        $this->createHistory();

        $this->errorManagementService->recordError(
            printedProductId: $this->printedProduct->getId(),
            process: Process::CUTTING_CHECK_IN,
            noticerId: $this->noticer->getId(),
        );

        $errors = $this->errorRepository->findByResponsibleEmployeeId($this->responsibleEmployee->getId());

        $this->assertCount(1, $errors);

        /** @var Error $error */
        foreach ($errors as $error) {
            $this->assertEquals($this->printedProduct->getId(), $error->printedProductId);
            $this->assertEquals(Process::CUTTING_CHECK_IN, $error->process);
            $this->assertEquals($this->noticer->getId(), $error->noticerId);
        }
    }

    /**
     * Test that an error can only be recorded for a roll with existing history.
     */
    public function test_roll_must_have_history_in_order_to_record_error(): void
    {
        $this->expectException(RollErrorManagementException::class);

        $this->errorManagementService->recordError(
            printedProductId: $this->printedProduct->getId(),
            process: Process::CUTTING_CHECK_IN,
            noticerId: $this->noticer->getId(),
        );
    }

    /**
     * @throws RollErrorManagementException
     */
    public function test_product_must_be_in_progress(): void
    {
        $printedProduct = $this->loadPrintedProduct();

        $this->expectException(NotFoundHttpException::class);

        $this->errorManagementService->recordError(
            printedProductId: $printedProduct->getId(),
            process: Process::CUTTING_CHECK_IN,
            noticerId: $this->noticer->getId(),
        );
    }

    /**
     * Create a new history entry and add it to the history repository.
     */
    private function createHistory(): void
    {
        $history = (new HistoryFactory())->make(
            rollId: $this->roll->getId(),
            process: Process::CUTTING_CHECK_IN,
            happenedAt: new \DateTimeImmutable(),
            type: Type::PROCESS_CHANGED,
            employeeId: $this->responsibleEmployee->getId(),
        );

        $this->historyRepository->add($history);
    }
}
