<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\UseCase\Query\FindAnOrder\FindAnOrderQuery;
use App\Orders\Application\UseCase\Query\FindAnOrder\FindAnOrderResult;
use App\Orders\Application\UseCase\Query\FindAProduct\FindAProductQuery;
use App\Orders\Application\UseCase\Query\FindAProduct\FindAProductResult;
use App\Orders\Application\UseCase\Query\FindExtras\FindExtrasQuery;
use App\Orders\Application\UseCase\Query\FindExtras\FindExtrasResult;
use App\Orders\Application\UseCase\Query\FindOrdersWithExtras\FindOrdersWithExtrasQuery;
use App\Orders\Application\UseCase\Query\FindOrdersWithExtras\FindOrdersWithExtrasResult;
use App\Orders\Application\UseCase\Query\FindPackedOrders\FindPackedOrdersQuery;
use App\Orders\Application\UseCase\Query\FindPackedOrders\FindPackedOrdersResult;
use App\Orders\Application\UseCase\Query\FindPartiallyPackedOrders\FindPartiallyPackedOrdersQuery;
use App\Orders\Application\UseCase\Query\FindPartiallyPackedOrders\FindPartiallyPackedOrdersResult;
use App\Orders\Application\UseCase\Query\FindProducts\FindProductsQuery;
use App\Orders\Application\UseCase\Query\FindProducts\FindProductsResult;
use App\Shared\Application\Query\QueryBusInterface;

/**
 * Class PrivateCommandInteractor.
 */
readonly class PrivateQueryInteractor
{
    /**
     * Class ExampleClass.
     */
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    /**
     * Class ExampleClass.
     *
     * @property QueryBusInterface $queryBus An instance of QueryBusInterface.
     */
    public function findAnOrder(string $id): FindAnOrderResult
    {
        $query = new FindAnOrderQuery($id);

        return $this->queryBus->execute($query);
    }

    /**
     * Finds the extras by executing the FindExtrasQuery.
     *
     * @param string $order the order ID
     *
     * @return FindExtrasResult the result of executing the FindExtrasQuery
     */
    public function findExtras(string $order): FindExtrasResult
    {
        return $this->queryBus->execute(new FindExtrasQuery($order));
    }

    /**
     * Finds products based on the provided query.
     *
     * @param FindProductsQuery $query The query object containing criteria for finding products
     *
     * @return FindProductsResult The result of finding products based on the query
     */
    public function findProducts(FindProductsQuery $query): FindProductsResult
    {
        return $this->queryBus->execute($query);
    }

    /**
     * Finds a specific product by its ID.
     *
     * @param string $productId the ID of the product to find
     *
     * @return FindAProductResult the result of the find product operation
     */
    public function findAProduct(string $productId): FindAProductResult
    {
        return $this->queryBus->execute(new FindAProductQuery($productId));
    }

    /**
     * Finds orders that are ready for packing.
     *
     * @return FindPackedOrdersResult the result of finding orders ready for packing
     */
    public function findPacked(): FindPackedOrdersResult
    {
        return $this->queryBus->execute(new FindPackedOrdersQuery());
    }

    /**
     * Finds partially packed orders.
     *
     * @return FindPartiallyPackedOrdersResult the result of finding partially packed orders
     */
    public function findPartiallyPacked(): FindPartiallyPackedOrdersResult
    {
        return $this->queryBus->execute(new FindPartiallyPackedOrdersQuery());
    }

    /**
     * Finds orders that only have extra items included.
     *
     * @return FindOrdersWithExtrasResult the result of finding orders with extras
     */
    public function findOrderOnlyWithExtras(): FindOrdersWithExtrasResult
    {
        return $this->queryBus->execute(new FindOrdersWithExtrasQuery());
    }
}
