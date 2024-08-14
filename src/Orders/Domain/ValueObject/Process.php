<?php

namespace App\Orders\Domain\ValueObject;

/**
 * Enum representing the different processes in the application.
 */
enum Process: string
{
    case ORDER_CHECK_IN = 'order_check_in';

    case PRINT_CHECK_IN = 'print_check_in';

    case LAMINATION_CHECK_IN = 'lamination_check_in';

    case CUT_CHECK_IN = 'cut_check_in';
}
