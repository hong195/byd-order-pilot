<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ManualProductsArrangement;

use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual\ManualProductsCheckInService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\DomainException;
use App\Shared\Domain\Service\AssertService;

final readonly class ManualProductsArrangementQueryHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private ManualProductsCheckInService $productsCheckInService, private AccessControlService $accessControlService)
    {
    }

    /**
     * @throws ManualArrangeException
     * @throws DomainException
     */
    public function __invoke(ManualProductsArrangementQuery $query): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');
        $this->productsCheckInService->arrange($query->printedProductIds);
    }
}
