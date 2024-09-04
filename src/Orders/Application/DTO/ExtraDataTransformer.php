<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

use App\Orders\Domain\Aggregate\Extra;

/**
 * ExtraData class represents product data.
 */
final readonly class ExtraDataTransformer
{
    /**
     * Converts an array of Extra objects to an array of ExtraData objects.
     *
     * @param Extra[] $products an array of Extra objects
     *
     * @return ExtraData[] an array of ExtraData objects
     */
    public function fromExtrasList(array $products): array
    {
        return array_map(
            fn (Extra $extra) => new ExtraData(
                $extra->getId(),
                $extra->name,
                $extra->orderNumber,
                $extra->isPacked()
            ),
            $products
        );
    }
}
