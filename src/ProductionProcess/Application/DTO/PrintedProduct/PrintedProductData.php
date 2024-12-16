<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\PrintedProduct;

/**
 * OrderData class represents order data.
 */
final readonly class PrintedProductData
{
    /**
     * Constructor for the Symfony application.
     *
     * @param int                     $id               the ID of the object
     * @param int                     $relatedProductId the ID of the related product
     * @param bool                    $hasPriority      flag indicating priority status
     * @param float                   $length           the length of the object
     * @param string                  $filmType         the type of film
     * @param string|null             $orderNumber      the order number, nullable
     * @param bool                    $isReprint        flag indicating reprint status, default is false
     * @param int|null                $rollId           the roll ID, nullable
     * @param string|null             $laminationType   the type of lamination, nullable
     * @param \DateTimeInterface|null $addedAt          the date when added, nullable
     * @param string|null             $cutFile          the cut file name, nullable
     * @param string|null             $printFile        the print file name, nullable
     * @param string|null             $photo            the product photo, nullable
     */
    public function __construct(
        public string $id,
        public int $relatedProductId,
        public bool $hasPriority,
        public float $length,
        public string $filmType,
        public ?string $orderNumber,
        public bool $isReprint = false,
        public ?int $rollId = null,
        public ?string $laminationType = null,
        public ?\DateTimeInterface $addedAt = null,
        public ?string $cutFile = null,
        public ?string $printFile = null,
        public ?string $photo = null,
		public bool $isPacked = false,
    ) {
    }
}
