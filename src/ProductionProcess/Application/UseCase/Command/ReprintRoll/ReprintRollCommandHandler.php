<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ReprintRoll;

use App\ProductionProcess\Domain\Exceptions\RollErrorManagementException;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\Error\ErrorManagementService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use App\Shared\Infrastructure\Security\UserFetcher;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class ReprintRollCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructor.
     */
    public function __construct(private ErrorManagementService $errorManagementService, private RollRepositoryInterface $rollRepository, private AccessControlService $accessControlService, private UserFetcher $userFetcher)
    {
    }

    /**
     * Handles a PackMainProductCommand.
     *
     * @param ReprintRollCommand $command The command to handle
     *
     * @throws NotFoundHttpException
     * @throws RollErrorManagementException
     */
    public function __invoke(ReprintRollCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $roll = $this->rollRepository->findById($command->rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        foreach ($roll->getPrintedProducts() as $printedProduct) {
            $this->errorManagementService->recordError($printedProduct->getId(), $roll->getProcess(), $this->userFetcher->requiredUserId());
        }
    }
}
