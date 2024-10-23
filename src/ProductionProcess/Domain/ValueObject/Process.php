<?php

namespace App\ProductionProcess\Domain\ValueObject;

/**
 * Enum representing the different processes in the application.
 */
enum Process: string
{
    case ORDER_CHECK_IN = 'order_check_in';

    case PRINTING_CHECK_IN = 'printing_check_in';

    case GLOW_CHECK_IN = 'glow_check_in';

    case CUTTING_CHECK_IN = 'cutting_check_in';

    // means after cutting check in roll no more exists, its sliced
    case CUT = 'cut';

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

    /**
     * Check if the current status is equal to the constant CUT to determine if the process is finished.
     *
     * @return bool returns true if the process is finished, otherwise false
     */
    public function isFinished(): bool
    {
        return self::CUTTING_CHECK_IN === $this;
    }
}
