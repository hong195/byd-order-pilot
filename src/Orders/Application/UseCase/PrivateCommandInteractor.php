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
     * @param string $productId The ID of the product to pack into the order
     */
    public function packProduct(string $productId): void
    {
        $this->commandBus->execute(new PackProductCommand(productId: $productId));
    }

    /**
     * Unpacks the main product for a given order and product.
     *
     * @param string $productId The ID of the product to unpack
     */
    public function unpackProduct(string $productId): void
    {
        $this->commandBus->execute(new UnPackProductCommand(productId: $productId));
    }

    /**
     * Packs or unpacks an extra for an order.
     *
     * @param string $orderId The ID of the order
     * @param string $extraId The ID of the extra
     */
    public function packExtra(string $orderId, string $extraId): void
    {
        $this->commandBus->execute(new PackExtraCommand(orderId: $orderId, extraId: $extraId));
    }

    /**
     * Unpacks an extra for a given order.
     *
     * @param string $orderId The ID of the order
     * @param string $extraId The ID of the extra to unpack
     */
    public function unPackExtra(string $orderId, string $extraId): void
    {
        $this->commandBus->execute(new UnPackExtraCommand(orderId: $orderId, extraId: $extraId));
    }

    /**
     * Adds a product to the application.
     *
     * @param AddProductCommand $command The command containing the product information
     */
    public function addProduct(AddProductCommand $command): string
    {
        return $this->commandBus->execute($command);
    }
}
