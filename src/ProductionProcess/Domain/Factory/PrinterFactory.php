<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Factory;

use App\ProductionProcess\Domain\Aggregate\Printer;
use App\ProductionProcess\Domain\ValueObject\FilmType;
use App\ProductionProcess\Domain\ValueObject\LaminationType;

final readonly class PrinterFactory
{
    /**
     * Makes a new item.
     *
     * @param string           $name            the name of the item
     * @param FilmType[]       $filmTypes       the roll types of the item
     * @param LaminationType[] $laminationTypes The lamination types of the item. (optional)
     */
    public function make(string $name, array $filmTypes = [], array $laminationTypes = []): Printer
    {
        return new Printer($name, $filmTypes, $laminationTypes);
    }
}
