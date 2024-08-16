<?php

namespace App\Orders\Domain\ValueObject;

/**
 * Enum representing the different processes in the application.
 */
enum Process: string
{
    case ORDER_CHECK_IN = 'order_check_in';

    case PRINTING = 'printing';

    case LAMINATION = 'lamination';

    case CUTTING = 'cutting';

    /**
     * Checks if the current process is equal to the given process.
     *
     * @param self $process the process to compare
     *
     * @return bool true if the processes are equal, false otherwise
     */
    public function equals(self $process): bool
    {
        return $this === $process;
    }
}
