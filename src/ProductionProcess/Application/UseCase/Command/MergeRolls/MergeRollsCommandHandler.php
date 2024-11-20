<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\MergeRolls;

use App\ProductionProcess\Domain\Exceptions\RollMergeException;
use App\ProductionProcess\Domain\Service\Roll\Merge\MergeService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\DomainException;
use App\Shared\Domain\Service\AssertService;

final readonly class MergeRollsCommandHandler implements CommandHandlerInterface
{
	/**
	 * Constructor for the Symfony application.
	 *
	 * @param AccessControlService $accessControlService The Access Control Service instance.
	 * @param MergeService $mergeService The Merge Service instance.
	 */
    public function __construct(private AccessControlService $accessControlService, private MergeService $mergeService)
    {
    }

	/**
	 * @throws RollMergeException
	 * @throws DomainException
	 */
	public function __invoke(MergeRollsCommand $command): void
    {
		AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

		$this->mergeService->merge($command->rollIds);
    }
}
