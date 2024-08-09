<?php

declare(strict_types=1);

namespace App\Orders\Domain\ValueObject;

/**
 * Enum Status represents the possible statuses of an order and order stack.
 */
enum Status: string
{
    case UNASSIGNED = 'unassigned';

    case ASSIGNED = 'assigned';

    case ORDER_CHECK_IN = 'order_check_in';

    case PRINT_CHECK_IN = 'print_check_in';

    case LAMINATION_CHECK_IN = 'lamination_check_in';

    case CUT_CHECK_IN = 'cut_check_in';

    case COLLECT_PREPARATION_FOR_SHIPPING = 'collect_preparation_for_shipping';
}
