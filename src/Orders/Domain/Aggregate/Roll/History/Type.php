<?php

namespace App\Orders\Domain\Aggregate\Roll\History;

/**
 * @enum Type
 *
 * @description Represents different types of events.
 *
 */
enum Type: string
{
    case EMPLOYEE_ASSIGNED = 'employee-assigned';

    case EMPLOYEE_UNASSIGNED = 'employee-unassigned';

    case PROCESS_CHANGED = 'process-changed';
}
