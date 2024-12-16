<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ChangePrintedProductPriority;

use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class ChangePrintedProductPriorityCommandHandler implements CommandHandlerInterface
{
	/**
	 * Class constructor.
	 *
	 * @param PrintedProductRepositoryInterface $printedProductRepository The printed product repository.
	 */
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository)
    {
    }

    /**
     * Invokes the command to change the order priority.
     *
     * @param ChangePrintedProductPriorityCommand $command the change order priority command instance
     *
     * @throws NotFoundHttpException if the roll is not found
     */
    public function __invoke(ChangePrintedProductPriorityCommand $command): void
    {
        $printedProduct = $this->printedProductRepository->findById($command->id);

        if (!$printedProduct) {
            throw new NotFoundHttpException('Printed product not found');
        }

		$printedProduct->updatePriority($command->priority);
        $this->printedProductRepository->save($printedProduct);
    }
}
