<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\Service\PrintedProduct;

use App\ProductionProcess\Application\DTO\PrintedProduct\CreatedPrintedProductData;
use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;

/**
 * Class RollMaker.
 *
 * Description: This class is responsible for creating rolls using the RollFactory, assigning a printer to the roll, and saving it to the RollRepository.
 */
final readonly class PrintedProductMaker
{
    /**
     * Class constructor.
     *
     * @param PrintedProductRepositoryInterface $printedProductRepository the job repository instance
     */
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository)
    {
    }

    public function make(CreatedPrintedProductData $dto): PrintedProduct
    {
        $job = new PrintedProduct(
            relatedProductId: $dto->productId,
            orderNumber: $dto->orderNumber,
            filmType: $dto->filmType,
            length: $dto->length
        );

        if ($dto->laminationType) {
            $job->setLaminationType($dto->laminationType);
        }

        $this->printedProductRepository->save($job);

        return $job;
    }
}
