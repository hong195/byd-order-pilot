<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UnPackMainProduct;

use App\Orders\Domain\Exceptions\ProductPackException;
use App\Orders\Domain\Exceptions\ProductUnPackException;
use App\Orders\Domain\Service\Order\Product\UnPackProduct;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class UnPackProductCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private UnPackProduct $unPackMainProduct, private AccessControlService $accessControlService)
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
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');
        $this->unPackMainProduct->handle(productId: $command->productId);
    }
}
