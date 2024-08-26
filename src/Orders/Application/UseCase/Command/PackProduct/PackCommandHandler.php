<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\PackProduct;

use App\Orders\Domain\Exceptions\ProductCheckException;
use App\Orders\Domain\Service\Order\Product\PackProduct;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class PackCommandHandler implements CommandHandlerInterface
{
    /**
     * Class PackCommandHandler.
     */
    public function __construct(private AccessControlService $accessControlService, private PackProduct $packProduct)
    {
    }

    /**
     * Class CheckProductHandler.
     *
     * @throws ProductCheckException
     */
    public function __invoke(PackProductCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->packProduct->handle($command->orderId, $command->productId, $command->isPacked);
    }
}
