<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\PrintedProduct;

/**
 * OrderData class represents order data.
 */
final readonly class PrintedProductProcessData
{
    /**
     * Constructor for creating a new instance of the class.
     *
     * @param int    $relatedProductId the related product ID
     * @param int    $rollId           the roll ID
     * @param string $process          the process
     * @param bool   $isFinished       flag indicating if the process is finished (default is false)
     * @param bool   $isReprint        flag indicating if the process is a reprint (default is false)
     */
    public function __construct(
        public int $relatedProductId,
        public ?int $rollId,
        public ?string $process,
        public bool $isFinished = false,
        public bool $isReprint = false,
    ) {
    }
}
