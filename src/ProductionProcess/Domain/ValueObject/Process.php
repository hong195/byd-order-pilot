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
     * Generates an array representing the workflow chain in order.
     *
     * @return array the array representing the workflow chain with key-value pairs in order
     */
    private function chain(): array
    {
        return [
            self::ORDER_CHECK_IN->value => self::PRINTING_CHECK_IN,
            self::PRINTING_CHECK_IN->value => self::GLOW_CHECK_IN,
            self::GLOW_CHECK_IN->value => self::CUTTING_CHECK_IN,
            self::CUTTING_CHECK_IN->value => self::CUT,
        ];
    }
}
