<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindOrderStacks;

use App\Orders\Application\DTO\OrderStackTransformer;
use App\Orders\Domain\Repository\OrderStackFilter;
use App\Orders\Infrastructure\Repository\OrderStackRepository;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * Class FindOrderStackHandler
 * Implements the QueryHandlerInterface.
 *
 * Handles the FindOrderStackQuery to find a roll and returns the corresponding FindOrderStackResult.
 */
final readonly class FindOrderStacksHandler implements QueryHandlerInterface
{
    public function __construct(
        private OrderStackRepository $orderStackRepository,
        private OrderStackTransformer $dataTransformer
    ) {
    }

    /**
     * Class FindOrderStackQuery.
     *
     * This class represents a query for finding an order stack from SQLite database.
     */
    public function __invoke(FindOrderStacksQuery $orderStackQuery): FindOrderStacksResult
    {
        $orderStacks = $this->orderStackRepository->findQueried(new OrderStackFilter());

        $data = $this->dataTransformer->fromOrderStacksList($orderStacks);

        return new FindOrderStacksResult($data);
    }
}
