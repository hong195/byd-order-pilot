<?php

declare(strict_types=1);

namespace App\Rolls\Domain\Factory;

use App\Rolls\Domain\Aggregate\Lamination\LaminationType;
use App\Rolls\Domain\Aggregate\OrderStack\OrderStack;
use App\Rolls\Domain\Aggregate\Roll\RollType;
use App\Rolls\Domain\Service\SortOrdersServiceInterface;

/**
 * Factory class for creating OrderStack objects.
 */
final readonly class OrderStackFactory
{
    /**
     * Construct SortOrdersController class.
     *
     * @param SortOrdersServiceInterface $sortOrdersService the SortOrdersServiceInterface object
     *
     * @return void
     */
    public function __construct(private SortOrdersServiceInterface $sortOrdersService)
    {
    }

    /**
     * Create a new OrderStack object.
     *
     * @param string      $name           the name of the order
     * @param string      $rollType       the roll type of the order
     * @param int         $length         the length of the order
     * @param int         $priority       the priority of the order
     * @param string|null $laminationType the lamination type of the order
     *
     * @return OrderStack the created OrderStack object
     */
    public function make(
        string $name,
        string $rollType,
        int $length,
        int $priority,
        ?string $laminationType,
    ): OrderStack {
        return new OrderStack(
            name: $name,
            length: $length,
            priority: $priority,
            rollType: RollType::from($rollType),
            sortOrdersService: $this->sortOrdersService,
            laminationType: LaminationType::from($laminationType),
        );
    }
}
