<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Exceptions;

use App\Shared\Domain\Exception\DomainException;

final class UnassignedPrintedProductsException extends DomainException
{
    /**
     * @var int[]
     */
    private array $unassignedPrintedProductIds;

    public function __construct(array $unassignedPrintedProductIds, string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $this->unassignedPrintedProductIds = $unassignedPrintedProductIds;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @throws UnassignedPrintedProductsException
     */
    public static function because(string $reason, array $unassignedPrintedProductIds = []): void
    {
        throw new UnassignedPrintedProductsException($unassignedPrintedProductIds, $reason);
    }

    /**
     * Method to retrieve an array of unassigned printed product IDs.
     *
     * @return int[] an array containing unassigned printed product IDs
     */
    public function unassignedPrintedProductIds(): array
    {
        return $this->unassignedPrintedProductIds;
    }
}
