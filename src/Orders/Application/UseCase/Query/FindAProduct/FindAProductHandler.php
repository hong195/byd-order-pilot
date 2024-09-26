<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindAProduct;

use App\Orders\Application\DTO\Product\ProductDataTransformer;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class FindOrdersHandler.
 */
final readonly class FindAProductHandler implements QueryHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private AccessControlService $accessControlService,
        private ProductDataTransformer $productDataTransformer,
    ) {
    }

    /**
     * Invokes the class.
     *
     * @param FindAProductQuery $query the find products query instance
     *
     * @return FindAProductResult the find products result instance
     */
    public function __invoke(FindAProductQuery $query): FindAProductResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $result = $this->productRepository->findById($query->productId);

        $productsData = $this->productDataTransformer->fromEntity($result);

        return new FindAProductResult($productsData);
    }
}
