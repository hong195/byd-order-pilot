<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindARoll;

use App\Orders\Application\DTO\LaminationData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindALaminationResult
{
    /**
     * @param LaminationData $laminationData the roll data object
     *
     * @return void
     */
    public function __construct(public LaminationData $laminationData)
    {
    }
}
