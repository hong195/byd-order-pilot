<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\CheckRemainingProductsService;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

final class CheckRemainingProductsServiceTest extends AbstractTestCase
{
    use FixtureTools;
    use FakerTools;

    private CheckRemainingProductsService $checkRemainingProductsService;
    private RollRepositoryInterface $rollRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
        $this->checkRemainingProductsService = self::getContainer()->get(CheckRemainingProductsService::class);
    }

    public function test_it_remove_roll_if_there_are_no_printed_product_left()
    {
        $roll = $this->loadRoll();
        $rollId = $roll->getId();

        $this->checkRemainingProductsService->check($rollId);

        $result = $this->rollRepository->findById($rollId);

        self::assertNull($result);
    }

	public function test_it_throws_exception_if_roll_does_not_exists()
	{
		$roll = $this->loadRoll();
		$rollId = $roll->getId();

		$this->rollRepository->remove($roll);

		$this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

		$this->checkRemainingProductsService->check($rollId);
	}
}
