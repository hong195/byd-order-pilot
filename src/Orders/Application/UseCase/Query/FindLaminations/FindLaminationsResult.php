<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindLaminations;

use App\Orders\Application\DTO\LaminationData;

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
