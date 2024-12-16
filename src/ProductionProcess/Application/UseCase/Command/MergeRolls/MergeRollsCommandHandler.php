<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\MergeRolls;

use App\ProductionProcess\Domain\Exceptions\RollMergeException;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Merge\MergeService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\DomainException;
use App\Shared\Domain\Service\AssertService;

final readonly class MergeRollsCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructor for the Symfony application.
     *
     * @param MergeService         $mergeService         the Merge Service instance
     */
    public function __construct(private MergeService $mergeService)
    {
    }

    /**
     * @throws RollMergeException
     * @throws DomainException
     */
    public function __invoke(MergeRollsCommand $command): void
    {
        $this->mergeService->merge($command->rollIds);
    }
}
