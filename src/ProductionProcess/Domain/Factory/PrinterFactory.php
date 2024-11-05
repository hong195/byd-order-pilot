<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Factory;

use App\ProductionProcess\Domain\Aggregate\Printer\Printer;

final readonly class PrinterFactory
{
    /**
     * Creates a new instance of the Printer class.
     *
     * @param string $name    the name for the Printer instance
     * @param bool   $isDefault the default value for the Printer instance (optional, default is false)
     *
     * @return Printer a new instance of the Printer class with the specified name and default value
     */
    public function make(string $name, bool $isDefault = false): Printer
    {
        return new Printer($name, $isDefault);
    }
}
