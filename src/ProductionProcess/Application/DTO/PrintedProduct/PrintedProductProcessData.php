<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\PrintedProduct;

/**
 * OrderData class represents order data.
 */
final readonly class PrintedProductProcessData
{
    /**
     * Class constructor.
     *
     * @param int    $relatedProductId the ID of the related product
     * @param int    $rollId           the ID of the roll
     * @param string $process          the process being performed
     * @param bool   $isReprint        Whether the object is a reprint. Default is false.
     */
    public function __construct(
        public int $relatedProductId,
        public int $rollId,
        public string $process,
        public bool $isReprint = false,
    ) {
    }
}
