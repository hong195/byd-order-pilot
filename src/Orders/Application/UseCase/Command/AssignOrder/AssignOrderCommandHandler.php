<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\AssignOrder;

use App\Orders\Domain\Service\CheckInProcess\CheckInInterface;
use App\Orders\Domain\Service\Order\ChangeStatusOrder;
use App\Orders\Domain\ValueObject\Status;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class AssignOrderCommandHandler implements CommandHandlerInterface
{
    /**
     * Class MyClass.
     */
    public function __construct(private ChangeStatusOrder $changeStatusOrder, private AccessControlService $accessControlService, private CheckInInterface $checkInService)
    {
    }

    /**
     * Invokes the command to change the order priority.
     *
     * @param AssignOrderCommand $command the change order priority command instance
     *
     * @throws NotFoundHttpException if the roll is not found
     */
    public function __invoke(AssignOrderCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not change priority.');

        $this->changeStatusOrder->handle($command->id, Status::ASSIGNED);

        $this->checkInService->checkIn();
    }
}
