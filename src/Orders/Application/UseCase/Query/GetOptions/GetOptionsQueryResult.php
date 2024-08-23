<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\GetOptions;

/**
 * Represents the result of finding a roll.
 */
final readonly class GetOptionsQueryResult
{
    /**
     * Class constructor.
     *
     * @param array<string, string> $items the roll data
     */
    public function __construct(public array $items)
    {
    }
}
