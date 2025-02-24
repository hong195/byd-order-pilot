<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\PackExtra;

use App\Orders\Domain\Exceptions\CantPackExtraException;
use App\Orders\Domain\Service\Order\Extra\SetExtraPackStatusService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class PackExtraCommandHandler.
 *
 * Handles the PackExtraCommand by invoking the SetExtraPackStatusService service.
 */
final class PackExtraCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private SetExtraPackStatusService $setPackExtra, private AccessControlService $accessControlService)
    {
    }

    /**
     * Invokes the command handler for PackExtraCommand.
     *
     * @param PackExtraCommand $command the command to be invoked
     *
     * @throws \RuntimeException      if no access to handle the command
     * @throws CantPackExtraException
     */
    public function __invoke(PackExtraCommand $command): void
    {
        $this->setPackExtra->handle(orderId: $command->orderId, extraId: $command->extraId, isPacked: $command->isPacked);
    }
}
