<?php

namespace App\Tests\Unit\ProductionProcess\Domain\Repository;

use App\ProductionProcess\Domain\Aggregate\Roll\History\Type;
use App\ProductionProcess\Domain\Factory\HistoryFactory;
use App\ProductionProcess\Domain\Repository\HistoryRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HistoryRepositoryTest extends KernelTestCase
{
    public const FAKE_EMPLOYEE_ID = 1;
    public const FAKE_ROLL_ID = 1;
    private HistoryRepositoryInterface $repository;

    /**
     * Set up the test fixture before each test method is called.
     *
     * This method is called before each test method execution to set up the necessary resources and environment.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        parent::setUp();

        $this->repository = $this->getContainer()->get(HistoryRepositoryInterface::class);
    }

    public function test_add(): void
    {
        $history = (new HistoryFactory())->make(
            rollId: self::FAKE_ROLL_ID,
            process: Process::PRINTING_CHECK_IN,
            happenedAt: new \DateTimeImmutable(),
            type: Type::EMPLOYEE_ASSIGNED,
            employeeId: self::FAKE_EMPLOYEE_ID
        );

        $this->repository->add($history);

        $addedHistory = $this->repository->findByRollId(self::FAKE_ROLL_ID)[0];

        $this->assertSame($history, $addedHistory);
    }
}
