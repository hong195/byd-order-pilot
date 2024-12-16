<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CheckRemainingProducts;

use App\ProductionProcess\Domain\Service\Roll\CheckRemainingProductsService;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class CheckRemainingProductsCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructor.
     */
    public function __construct(private CheckRemainingProductsService $checkRemainingProductsService)
    {
    }

    /**
     * Check the remaining products in the SQLite database.
     */
    public function __invoke(CheckRemainingProductsCommand $command): void
    {
        $this->checkRemainingProductsService->check($command->rollId);
    }
}
