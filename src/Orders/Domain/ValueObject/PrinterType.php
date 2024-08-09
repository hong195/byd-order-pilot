<?php

namespace App\Orders\Domain\ValueObject;

enum PrinterType: string
{
    case ROLAND_UV_PRINTER = 'roland_uv_printer';

    case MIMAKI_UV_PRINTER = 'mimaki_uv_printer';

    case ROLAND_NORMAL_PRINTER = 'roland_normal_printer';
}
