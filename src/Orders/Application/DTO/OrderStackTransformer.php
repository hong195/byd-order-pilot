<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

use App\Orders\Domain\Aggregate\OrderStack\OrderStack;

/**
 * Converts an OrderStack entity to an OrderStackData object.
 *
 * @param OrderStack $orderStack the OrderStack entity to convert
 *
 * @return OrderStackData the converted OrderStackData object
 */
final readonly class OrderStackTransformer
{
    /**
     * Converts a list of OrderStack entities to an array of OrderStackData objects.
     *
     * @param array<OrderStack> $orderStacks the list of OrderStack entities to convert
     *
     * @return array<OrderStackData> the array of converted OrderStackData objects
     */
    public function fromOrderStacksList(array $orderStacks): array
    {
        $orderStacksData = [];
        foreach ($orderStacks as $orderStack) {
            $orderStacksData[] = $this->fromEntity($orderStack);
        }

        return $orderStacksData;
    }

    /**
     * Converts an OrderStack entity to an OrderStackData object.
     *
     * @param OrderStack $orderStack the OrderStack entity to convert
     *
     * @return OrderStackData the converted OrderStackData object
     */
    public function fromEntity(OrderStack $orderStack): OrderStackData
    {
        return new OrderStackData(
            id: $orderStack->getId(),
            name: $orderStack->getName(),
            length: $orderStack->getLength(),
            priority: $orderStack->getPriority(),
            rollType: $orderStack->getRollType()->value,
            dateAdded: $orderStack->getDateAdded(),
            updatedAt: $orderStack->getUpdatedAt(),
            laminationType: $orderStack->getLaminationType()?->value,
        );
    }
}
