<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Printer;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
use App\Orders\Domain\ValueObject\PrinterType;

final readonly class PrinterFactory
{
    /**
     * Makes a new item.
     *
     * @param PrinterType      $name            the name of the item
     * @param FilmType[]       $filmTypes       the roll types of the item
     * @param LaminationType[] $laminationTypes The lamination types of the item. (optional)
     */
    public function make(PrinterType $name, array $filmTypes = [], array $laminationTypes = []): Printer
    {
        return new Printer($name, $filmTypes, $laminationTypes);
    }
}
