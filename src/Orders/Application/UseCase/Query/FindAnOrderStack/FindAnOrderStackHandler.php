<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindAnOrderStack;

use App\Orders\Application\DTO\OrderStackTransformer;
use App\Orders\Infrastructure\Repository\OrderStackRepository;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * Handles the FindARollQuery and returns the FindARollResult.
 */
final readonly class FindAnOrderStackHandler implements QueryHandlerInterface
{
    /**
     * Constructor method for the class.
     *
     * @param OrderStackRepository  $orderStackRepository - An instance of the OrderStackRepository class
     * @param OrderStackTransformer $dataTransformer      - An instance of the OrderStackTransformer class
     */
    public function __construct(private OrderStackRepository $orderStackRepository, private OrderStackTransformer $dataTransformer)
    {
    }

    /**
     * Class FindOrderStackQuery.
     *
     * This class represents a query for finding an order stack from SQLite database.
     */
    public function __invoke(FindAnOrderStackQuery $orderStackQuery): FindAnOrderStackResult
    {
        $orderStack = $this->orderStackRepository->findById($orderStackQuery->orderStackId);

        $data = $this->dataTransformer->fromEntity($orderStack);

        return new FindAnOrderStackResult($data);
    }
}
