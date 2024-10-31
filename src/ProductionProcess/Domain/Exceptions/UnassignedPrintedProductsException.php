<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Exceptions;

final class UnassignedPrintedProductsException extends \Exception
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
        $exception = throw new self($unassignedPrintedProductIds, $reason);
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
