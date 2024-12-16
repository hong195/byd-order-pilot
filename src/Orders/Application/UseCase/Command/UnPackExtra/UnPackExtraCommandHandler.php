<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UnPackExtra;

use App\Orders\Domain\Exceptions\CantPackExtraException;
use App\Orders\Domain\Service\Order\Extra\SetExtraPackStatusService;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class UnPackExtraCommandHandler.
 */
final class UnPackExtraCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     */
    public function __construct(private SetExtraPackStatusService $setPackExtra)
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
        $this->setPackExtra->handle(orderId: $command->orderId, extraId: $command->extraId, isPacked: $command->isPacked);
    }
}
