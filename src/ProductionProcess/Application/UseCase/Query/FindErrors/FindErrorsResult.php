<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindErrors;

use App\ProductionProcess\Application\DTO\Error\ErrorData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindErrorsResult
{
    /**
     * Class constructor.
     *
     * @param ErrorData[] $items the roll data
     */
    public function __construct(public iterable $items)
    {
    }
}
