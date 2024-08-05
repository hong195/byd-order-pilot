<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Printer;
use App\Orders\Domain\Aggregate\Roll\RollType;
use App\Orders\Domain\Aggregate\ValueObject\LaminationType;
use App\Orders\Domain\Aggregate\ValueObject\PrinterType;

final readonly class PrinterFactory
{
    /**
     * Makes a new item.
     *
     * @param PrinterType      $name            the name of the item
     * @param RollType[]       $rollTypes       the roll types of the item
     * @param LaminationType[] $laminationTypes The lamination types of the item. (optional)
     */
    public function make(PrinterType $name, array $rollTypes = [], array $laminationTypes = []): Printer
    {
        return new Printer($name, $rollTypes, $laminationTypes);
    }
}
