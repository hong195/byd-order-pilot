<?php

namespace App\ProductionProcess\Domain\ValueObject;

enum PrinterType: string
{
    case ROLAND_UV_PRINTER = 'roland_uv_printer';

    case MIMAKI_UV_PRINTER = 'mimaki_uv_printer';

    case ROLAND_NORMAL_PRINTER = 'roland_normal_printer';

    /**
     * Get the name of the printer.
     *
     * @return string The name of the printer
     */
    public function getName(): string
    {
        return match ($this) {
            self::ROLAND_NORMAL_PRINTER => 'Roland Normal Printer',
            self::ROLAND_UV_PRINTER => 'Roland UV Printer',
            self::MIMAKI_UV_PRINTER => 'Mimaki UV Printer',
        };
    }
}
