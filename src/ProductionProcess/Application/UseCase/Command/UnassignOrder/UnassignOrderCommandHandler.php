<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UnassignOrder;

use App\Orders\Domain\Service\Order\UnassignOrder;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class UnassignOrderCommandHandler implements CommandHandlerInterface
{
    /**
     * Class MyClass.
     */
    public function __construct(private UnassignOrder $unassignOrder, private AccessControlService $accessControlService)
    {
    }

    /**
     * Class ClassName.
     */
    public function __invoke(UnassignOrderCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not change priority.');

        $this->unassignOrder->handle($command->id);
    }
}
