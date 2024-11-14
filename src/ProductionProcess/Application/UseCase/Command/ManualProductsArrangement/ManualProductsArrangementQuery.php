<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ManualProductsArrangement;

use App\Shared\Application\Command\CommandInterface;

final readonly class ManualProductsArrangementQuery implements CommandInterface
{
    /**
     * Class constructor for initializing the object with printed product IDs.
     *
     * @param int[] $printedProductIds The array of printed product IDs. Defaults to an empty array.
     */
    public function __construct(public array $printedProductIds = [])
    {
    }
}
