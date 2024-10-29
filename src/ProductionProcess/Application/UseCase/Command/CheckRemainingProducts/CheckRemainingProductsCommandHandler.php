<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CheckRemainingProducts;

use App\ProductionProcess\Domain\Exceptions\RollErrorManagementException;
use App\ProductionProcess\Domain\Service\Roll\CheckRemainingProductsService;
use App\ProductionProcess\Domain\Service\Roll\ReprintRollService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class CheckRemainingProductsCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructor.
     */
    public function __construct(private CheckRemainingProductsService $checkRemainingProductsService, private AccessControlService $accessControlService)
    {
    }

	/**
	 * Check the remaining products in the SQLite database
	 *
	 * @param CheckRemainingProductsCommand $command
	 * @return void
	 */
    public function __invoke(CheckRemainingProductsCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->checkRemainingProductsService->check($command->rollId);
    }
}
