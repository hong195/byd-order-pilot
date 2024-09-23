<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Repository;

use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * RollFilter class is used to filter rolls based on roll type and lamination type.
 */
readonly class RollFilter
{
    /**
     * Constructor method.
     *
     * @param Process|null $process  The process object. (optional)
     * @param int[]        $filmIds  The array of film IDs. (optional)
     * @param string|null  $filmType The type of film. (optional)
     */
    public function __construct(public ?Process $process = null, public array $filmIds = [], public ?string $filmType = null)
    {
    }
}
