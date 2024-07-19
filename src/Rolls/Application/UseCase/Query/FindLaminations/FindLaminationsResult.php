<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindLaminations;

use App\Rolls\Application\DTO\LaminationData;
use App\Rolls\Application\DTO\RollData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindLaminationsResult
{
    /**
     * Class constructor.
     *
     * @param LaminationData[] $items the roll data
     */
    public function __construct(public array $items)
    {
    }
}
