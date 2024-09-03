<?php

declare(strict_types=1);


namespace App\Orders\Application\UseCase\Command\UnPackExtra;

use App\Orders\Domain\Exceptions\CantPackExtraException;
use App\Orders\Domain\Service\Order\Extra\SetPackExtra;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class UnPackExtraCommandHandler
 * @package App\CommandHandler
 *
 * Handles the UnPackExtraCommand.
 */
final  class UnPackExtraCommandHandler implements CommandHandlerInterface
{
	/**
	 * Class constructor.
	 *
	 * @param AccessControlService $accessControlService the access control service
	 */
	public function __construct(private SetPackExtra $setPackExtra, private AccessControlService $accessControlService)
	{
	}

	/**
	 * Invokes the command handler for PackExtraCommand.
	 *
	 * @param UnPackExtraCommand $command the command to be invoked
	 *
	 * @throws \RuntimeException      if no access to handle the command
	 * @throws CantPackExtraException
	 */
	public function __invoke(UnPackExtraCommand $command): void
	{
		AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');
		$this->setPackExtra->handle(orderId: $command->orderId, extraId: $command->extraId, isPacked: $command->isPacked);
	}
}