<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ManualProductsArrangement;

use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual\ManualProductsCheckInService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\DomainException;

final readonly class ManualProductsArrangementQueryHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     */
    public function __construct(private ManualProductsCheckInService $productsCheckInService)
    {
    }

    /**
     * @throws ManualArrangeException
     * @throws DomainException
     */
    public function __invoke(ManualProductsArrangementQuery $query): void
    {
        $this->productsCheckInService->arrange($query->printedProductIds);
    }
}
