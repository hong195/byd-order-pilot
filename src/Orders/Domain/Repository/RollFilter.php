<?php

declare(strict_types=1);

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\ValueObject\Process;

/**
 * RollFilter class is used to filter rolls based on roll type and lamination type.
 */
readonly class RollFilter
{
    /**
     * Class constructor.
     *
     * @param Process|null $process  The process object. If null, it means no process is set.
     * @param array        $filmIds  An array of film ids. It is an optional parameter and defaults to an empty array.
     * @param string|null  $rollType The roll type. If null, it means no roll type is set.
     */
    public function __construct(public ?Process $process = null, public array $filmIds = [], public ?string $rollType = null)
    {
    }
}
