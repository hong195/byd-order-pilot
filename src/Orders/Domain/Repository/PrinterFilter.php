<?php

declare(strict_types=1);

namespace App\Orders\Domain\Repository;

/**
 * Class OrderStackFilter.
 *
 * Filter for ordering stacks
 */
final class PrinterFilter
{
    /**
     * Class Constructor.
     *
     * @param string[] $filmTypes
     * @param string[] $laminationTypes
     */
    public function __construct(public readonly array $filmTypes = [], public array $laminationTypes = [])
    {
    }

    public function setLaminationType(array $laminationTypes): void
    {
        $this->laminationTypes = $laminationTypes;
    }
}
