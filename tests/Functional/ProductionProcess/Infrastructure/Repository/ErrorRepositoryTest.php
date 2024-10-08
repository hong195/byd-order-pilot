<?php

namespace App\Tests\Functional\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\Error;
use App\ProductionProcess\Domain\Factory\ErrorFactory;
use App\ProductionProcess\Domain\Repository\ErrorRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Tests\Functional\AbstractTestCase;

class ErrorRepositoryTest extends AbstractTestCase
{
    private const FAKE_NOTICER_ID = 1;
    private const FAKE_RESPONSIBLE_EMPLOYEE_ID = 2;
    private const FAKE_PRINTED_PRODUCT_ID = 1;
    private ErrorRepositoryInterface $repository;

    private Error $error1;
    private Error $error2;

    /**
     * Set up the test fixture before each test method is called.
     *
     * This method is called before each test method execution to set up the necessary resources and environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->initErrors();

        $this->repository = $this->getContainer()->get(ErrorRepositoryInterface::class);
    }

    /**
     * Test the add method in the repository.
     *
     * This method tests the add functionality of the repository by creating two Error objects using ErrorFactory,
     * adding them to the repository, and then asserting that the added errors can be retrieved by the responsible employee ID.
     */
    public function test_add(): void
    {
        $this->repository->add($this->error1);
        $this->repository->add($this->error2);

        $addedErrors = $this->repository->findByResponsibleEmployeeId(self::FAKE_RESPONSIBLE_EMPLOYEE_ID);

        $this->assertContains($this->error1, $addedErrors);
        $this->assertContains($this->error2, $addedErrors);
    }

    /**
     * Test finding errors by process in the repository.
     */
    public function test_find_by_process(): void
    {
        $this->repository->add($this->error1);
        $this->repository->add($this->error2);

        $foundErrors = $this->repository->findByProcess(Process::PRINTING_CHECK_IN);

        foreach ($foundErrors as $error) {
            $this->assertSame($this->error1->process, $error->process);
        }
    }

    /**
     * Initialize errors for testing purposes.
     */
    private function initErrors(): void
    {
        $this->error1 = (new ErrorFactory())->make(
            printedProductId: self::FAKE_PRINTED_PRODUCT_ID,
            process: Process::PRINTING_CHECK_IN,
            responsibleEmployeeId: self::FAKE_RESPONSIBLE_EMPLOYEE_ID,
            noticerId: self::FAKE_NOTICER_ID
        )
            ->build();

        $this->error2 = (new ErrorFactory())->make(
            printedProductId: self::FAKE_PRINTED_PRODUCT_ID,
            process: Process::CUTTING_CHECK_IN,
            responsibleEmployeeId: self::FAKE_RESPONSIBLE_EMPLOYEE_ID,
            noticerId: self::FAKE_NOTICER_ID
        )
            ->build();
    }
}
