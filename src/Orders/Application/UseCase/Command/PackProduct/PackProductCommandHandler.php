<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\PackProduct;

use App\Orders\Domain\Exceptions\ProductPackException;
use App\Orders\Domain\Service\Order\Product\PackProduct;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class PackProductCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructor for initializing a new instance.
     *
     * @param PackProduct          $packMainProduct      the main pack product to be injected
     * @param AccessControlService $accessControlService the access control service to be injected
     */
    public function __construct(private PackProduct $packMainProduct)
    {
    }

    /**
     * Invokes the command handler.
     *
     * @param PackProductCommand $command the pack product command to handle
     *
     * @throws \RuntimeException            when access control service denies access
     * @throws ProductPackException
     */
    public function __invoke(PackProductCommand $command): void
    {
        $this->packMainProduct->handle(productId: $command->productId);
    }
}
