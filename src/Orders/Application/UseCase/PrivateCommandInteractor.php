<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\DTO\Order\SortOrderData;
use App\Orders\Application\UseCase\Command\AddProduct\AddProductCommand;
use App\Orders\Application\UseCase\Command\AddProduct\CreatePrintedProductCommand;
use App\Orders\Application\UseCase\Command\AssignOrder\AssignOrderCommand;
use App\Orders\Application\UseCase\Command\ChangeOrderPriority\ChangePrintedProductPriorityCommand;
use App\Orders\Application\UseCase\Command\ChangeOrderSort\ChangePrintedProductSortCommand;
use App\Orders\Application\UseCase\Command\CreateExtra\CreateExtraCommand;
use App\Orders\Application\UseCase\Command\PackExtra\PackExtraCommand;
use App\Orders\Application\UseCase\Command\PackMainProduct\PackMainProductCommand;
use App\Orders\Application\UseCase\Command\ReprintOrder\ReprintOrderCommand;
use App\Orders\Application\UseCase\Command\ShipAndCollectOrders\ShipAndCollectOrdersCommand;
use App\Orders\Application\UseCase\Command\UnassignOrder\UnassignPrintedProductCommand;
use App\Orders\Application\UseCase\Command\UnPackExtra\UnPackExtraCommand;
use App\Orders\Application\UseCase\Command\UnPackMainProduct\UnPackMainProductCommand;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PrivateCommandInteractor.
 */
readonly class PrivateCommandInteractor
{
    /**
     * Class ExampleClass.
     */
    public function __construct(private CommandBusInterface $commandBus)
    {
    }
    /**
     * Creates an extra.
     *
     * @param CreateExtraCommand $command The command to create an extra
     */
    public function createExtra(CreateExtraCommand $command): void
    {
        $this->commandBus->execute($command);
    }

    /**
     * Packs the main product of an order.
     *
     * @param int $rollId  The ID of the roll to be packed
     * @param int $orderId The ID of the order to which the roll belongs
     */
    public function packMainProduct(int $rollId, int $orderId): void
    {
        $this->commandBus->execute(new PackMainProductCommand($rollId, $orderId));
    }

    /**
     * Unpacks the main product from a roll for an order.
     *
     * @param int $rollId  The ID of the roll containing the main product
     * @param int $orderId The ID of the order
     */
    public function unpackMainProduct(int $rollId, int $orderId): void
    {
        $this->commandBus->execute(new UnPackMainProductCommand($rollId, $orderId));
    }

    /**
     * Packs or unpacks an extra for an order.
     *
     * @param int $orderId The ID of the order
     * @param int $extraId The ID of the extra
     */
    public function packExtra(int $orderId, int $extraId): void
    {
        $this->commandBus->execute(new PackExtraCommand(orderId: $orderId, extraId: $extraId));
    }

    /**
     * Unpacks an extra for a given order.
     *
     * @param int $orderId The ID of the order
     * @param int $extraId The ID of the extra to unpack
     */
    public function unPackExtra(int $orderId, int $extraId): void
    {
        $this->commandBus->execute(new UnPackExtraCommand(orderId: $orderId, extraId: $extraId));
    }

    /**
     * Changes the sort order of an order.
     *
     * @param SortOrderData $orderData The data containing the roll ID, order ID, and sort order
     */
    public function changeSortOrder(SortOrderData $orderData): void
    {
        $command = new ChangePrintedProductSortCommand(rollId: $orderData->rollId, group: $orderData->group, sortOrders: $orderData->sortOrders);
        $this->commandBus->execute($command);
    }

    /**
     * Adds a product to the application.
     *
     * @param AddProductCommand $command The command containing the product information
     */
    public function addProduct(AddProductCommand $command): int
    {
        return $this->commandBus->execute($command);
    }
}
