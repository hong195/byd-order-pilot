<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UnPackMainProduct;

use App\Orders\Domain\Exceptions\ProductPackException;
use App\Orders\Domain\Exceptions\ProductUnPackException;
use App\Orders\Domain\Service\Order\Product\UnPackProduct;
use App\Shared\Application\Command\CommandHandlerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class UnPackProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(private UnPackProduct $unPackMainProduct)
    {
    }

    /**
     * Handles a PackProductCommand.
     *
     * @param UnPackProductCommand $command The command to handle
     *
     * @throws NotFoundHttpException
     * @throws ProductPackException
     * @throws ProductUnPackException
     */
    public function __invoke(UnPackProductCommand $command): void
    {
        $this->unPackMainProduct->handle(productId: $command->productId);
    }
}
