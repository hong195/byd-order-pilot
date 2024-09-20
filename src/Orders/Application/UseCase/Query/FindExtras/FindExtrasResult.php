<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindExtras;

use App\Orders\Application\DTO\Extra\ExtraData;

/**
 * Represents the result of finding extras.
 */
final readonly class FindExtrasResult
{
    /**
     * Class constructor.
     *
     * @param ExtraData[] $extras the extras array
     */
    public function __construct(public array $extras)
    {
    }
}
