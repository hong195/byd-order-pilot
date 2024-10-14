<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\UseCase\Command\AddProduct\AddProductCommand;
use App\Orders\Application\UseCase\Command\CreateExtra\CreateExtraCommand;
use App\Orders\Application\UseCase\Command\PackExtra\PackExtraCommand;
use App\Orders\Application\UseCase\Command\PackProduct\PackProductCommand;
use App\Orders\Application\UseCase\Command\UnPackExtra\UnPackExtraCommand;
use App\Orders\Application\UseCase\Command\UnPackMainProduct\UnPackProductCommand;
use App\Shared\Application\Command\CommandBusInterface;

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
     * Packs a product into an order.
     *
     * @param int $orderId   The ID of the order to pack the product into
     * @param int $productId The ID of the product to pack into the order
     */
    public function packProduct(int $orderId, int $productId): void
    {
        $this->commandBus->execute(new PackProductCommand(orderId: $orderId, productId: $productId));
    }

    /**
     * Unpacks the main product for a given order and product.
     *
     * @param int $orderId   The ID of the order to unpack the product for
     * @param int $productId The ID of the product to unpack
     */
    public function unpackProduct(int $orderId, int $productId): void
    {
        $this->commandBus->execute(new UnPackProductCommand(orderId: $orderId, productId: $productId));
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
     * Adds a product to the application.
     *
     * @param AddProductCommand $command The command containing the product information
     */
    public function addProduct(AddProductCommand $command): int
    {
        return $this->commandBus->execute($command);
    }
}
