<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Exceptions\RollErrorManagementException;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\Error\ErrorManagementService;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Infrastructure\Security\UserFetcher;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ReprintRollService
{
    public function __construct(private ErrorManagementService $errorManagementService, private RollRepositoryInterface $rollRepository, private UserFetcher $userFetcher, private PrintedProductRepositoryInterface $printedProductRepository)
    {
    }

    /**
     * @throws RollErrorManagementException
     */
    public function reprint(int $rollId, string $process, ?string $reason): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        foreach ($roll->getPrintedProducts() as $printedProduct) {
            $printedProduct->reprint();

            $this->errorManagementService->recordError(
                printedProductId: $printedProduct->getId(),
                process: Process::from($process),
                noticerId: $this->userFetcher->requiredUserId(),
                reason: $reason
            );

            $this->printedProductRepository->save($printedProduct);
        }

        $this->rollRepository->remove($roll);
    }
}
