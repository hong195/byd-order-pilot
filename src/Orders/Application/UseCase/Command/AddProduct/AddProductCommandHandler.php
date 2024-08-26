<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\AddProduct;

use App\Orders\Domain\Exceptions\CantAddProductToOrderException;
use App\Orders\Domain\Service\Order\Product\ProductMaker;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class AddProductCommandHandler implements CommandHandlerInterface
{
    /**
     * Class MyClass.
     */
    public function __construct(private AccessControlService $accessControlService, private ProductMaker $productMaker)
    {
    }

	/**
	 * Class AddProductHandler.
	 *
	 * @package MyApp\Handler
	 */
	public function __invoke(AddProductCommand $command): int
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not change priority.');

        return $this->productMaker->make($command->orderId, FilmType::from($command->filmType), LaminationType::from($command->laminationType));
    }
}
