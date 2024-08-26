<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\DTO\SortOrderData;
use App\Orders\Application\UseCase\Command\AddProduct\AddProductCommand;
use App\Orders\Application\UseCase\Command\AssignOrder\AssignOrderCommand;
use App\Orders\Application\UseCase\Command\ChangeOrderPriority\ChangeOrderPriorityCommand;
use App\Orders\Application\UseCase\Command\ChangeOrderSort\ChangeOrderSortCommand;
use App\Orders\Application\UseCase\Command\ChangePrinterAvailability\ChangePrinterAvailabilityCommand;
use App\Orders\Application\UseCase\Command\CreatePrinters\CreatePrintersCommand;
use App\Orders\Application\UseCase\Command\CuttingCheckIn\CuttingCheckIntCommand;
use App\Orders\Application\UseCase\Command\GlowCheckIn\GlowCheckInCommand;
use App\Orders\Application\UseCase\Command\PackProduct\PackProductCommand;
use App\Orders\Application\UseCase\Command\PrintCheckIn\PrintCheckIntCommand;
use App\Orders\Application\UseCase\Command\ReprintOrder\ReprintOrderCommand;
use App\Orders\Application\UseCase\Command\ShipAndCollectOrders\ShipAndCollectOrdersCommand;
use App\Orders\Application\UseCase\Command\UnassignOrder\UnassignOrderCommand;
use App\Orders\Domain\Exceptions\OrderReprintException;
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
     * Create printers in the database.
     */
    public function createPrinters(): void
    {
        $command = new CreatePrintersCommand();
        $this->commandBus->execute($command);
    }

    /**
     * Changes the status of an order.
     *
     * @param bool $status The new status of the order
     */
    public function changeOrderPriority(int $id, bool $status): void
    {
        $this->commandBus->execute(new ChangeOrderPriorityCommand($id, $status));
    }

    /**
     * Unassigns an order.
     *
     * @param int $id The id of the order to unassign
     */
    public function unassignOrder(int $id): void
    {
        $this->commandBus->execute(new UnassignOrderCommand($id));
    }

    /**
     * Assigns an order. Triggers the check-in process.
     *
     * @param int $id The id of the order to assign
     */
    public function assignOrder(int $id): void
    {
        $this->commandBus->execute(new AssignOrderCommand($id));
    }

    /**
     * Changes the sort order of an order.
     *
     * @param SortOrderData $orderData The data containing the roll ID, order ID, and sort order
     */
    public function changeSortOrder(SortOrderData $orderData): void
    {
        $command = new ChangeOrderSortCommand(rollId: $orderData->rollId, group: $orderData->group, sortOrders: $orderData->sortOrders);
        $this->commandBus->execute($command);
    }

    /**
     * Makes a printer available.
     *
     * @param int $printerId The ID of the printer
     */
    public function makePrinterAvailable(int $printerId): void
    {
        $command = new ChangePrinterAvailabilityCommand(printerId: $printerId, isAvailable: true);
        $this->commandBus->execute($command);
    }

    /**
     * Makes a printer available.
     *
     * @param int $printerId The ID of the printer
     */
    public function makePrinterUnAvailable(int $printerId): void
    {
        $command = new ChangePrinterAvailabilityCommand(printerId: $printerId, isAvailable: false);
        $this->commandBus->execute($command);
    }

    /**
     * Sends a roll to print check-in.
     *
     * @param int $rollId The ID of the roll to send to print check-in
     */
    public function printingCheckIn(int $rollId): void
    {
        $this->commandBus->execute(new PrintCheckIntCommand($rollId));
    }

    /**
     * Sends a roll to the glow check-in process.
     *
     * @param int $rollId the ID of the roll to be sent
     */
    public function glowCheckIn(int $rollId): void
    {
        $this->commandBus->execute(new GlowCheckInCommand($rollId));
    }

    /**
     * Sends a roll to be checked in for cutting.
     *
     * @param int $rollId The ID of the roll to be checked in
     */
    public function cuttingCheckIn(int $rollId): void
    {
        $this->commandBus->execute(new CuttingCheckIntCommand($rollId));
    }

    /**
     * Ships and collects orders for a given roll.
     *
     * @param int $rollId The ID of the roll
     */
    public function shipAndCollectOrders(int $rollId): void
    {
        $this->commandBus->execute(new ShipAndCollectOrdersCommand($rollId));
    }

    /**
     * Prints a new copy of an order.
     *
     * @param int $rollId  The ID of the roll
     * @param int $orderId The ID of the order
     *
     * @throws NotFoundHttpException
     * @throws OrderReprintException
     */
    public function reprintOrder(int $rollId, int $orderId): void
    {
        $this->commandBus->execute(new ReprintOrderCommand($rollId, $orderId));
    }

    /**
     * Adds a product to an order.
     *
     * @param int         $orderId        The ID of the order
     * @param string      $filmType       The film type of the product
     * @param string|null $laminationType The lamination type of the product. Optional.
     *
     * @return int The ID of the added product
     */
    public function addProduct(int $orderId, string $filmType, ?string $laminationType = null): int
    {
        return $this->commandBus->execute(new AddProductCommand($orderId, $filmType, $laminationType));
    }

    /**
     * Checks the product status for a given order.
     *
     * @param int  $orderId   The ID of the order
     * @param int  $productId The ID of the product
     * @param bool $isPacked  The status of the product
     */
    public function packProduct(int $orderId, int $productId, bool $isPacked): void
    {
        $this->commandBus->execute(new PackProductCommand($orderId, $productId, $isPacked));
    }
}
