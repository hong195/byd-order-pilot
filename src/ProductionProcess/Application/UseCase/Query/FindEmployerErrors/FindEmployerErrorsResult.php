<?php

declare(strict_types=1);

/**
 * Represents the result of finding errors in the production process.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FindEmployerErrors;

use App\ProductionProcess\Application\DTO\Error\EmployeeErrorData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindEmployerErrorsResult
{
    /**
     * Class constructor.
     *
     * @param EmployeeErrorData[] $items the roll data
     */
    public function __construct(public array $items)
    {
    }
}
